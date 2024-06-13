<?php

namespace App\Modules\Mapping\Notifications;

use App\Bases\BaseService;
use App\Facades\Common;
use DataTables;
use App\Modules\Mapping\Notifications\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\LedReviewMail;
use App\Mail\NotifikasiMail;
use App\Modules\Admin\User\Model as UserModel;
use App\Modules\Penilaian\Rcsa\Penetapan\ModelRiskTreatment;
use App\Modules\Tools\Appconfig\Service as AppconfigService;
use App\Modules\Master\Mastertemplate\Service as MastertemplateService;
use App\Modules\Admin\User\Service as UserService;
use Illuminate\Mail\SentMessage;
use Exception, DB;
use stdClass;

class Service extends BaseService
{

    public function __construct()
    {
    }

    public function data(array $data)
    {
        $role = Auth::user()->roles->pluck('id')->toArray()[0];
        $user = Auth::user()->id;
        $is_reviewer = Auth::user()->is_reviewer;
        $unit_kerja_id = Auth::user()->unit_kerja_id;
        $unit_kerja_code = Auth::user()->unit_kerja_code;

        $query = Model::where(function ($query) use ($role, $user, $is_reviewer, $unit_kerja_code) {


            $query->where(function ($q) use ($role, $user) {
                $q->where('role_id', $role);
                $q->orWhere('user_id', $user);
            });

            $query->where('unit_kerja_code', $unit_kerja_code);
            $query->where('status', false);
        })
            ->limit(10)->orderBy('created_at', 'desc')->get();

        $results = [];

        foreach ($query as $item) {
            $results[] = [
                'url' => route('mapping.notification.read', ['id' => encrypt($item['id'])]),
                'msg' => $item['msg'],
                'status' => $item['status'],
                'type' => $item['type'],
                'created_at' => Carbon::parse($item['created_at'])->diffForHumans()
            ];
        }

        return $this->outputResult($results);
    }

    public function count(array $data)
    {
        $role = Auth::user()->roles->pluck('id')->toArray()[0];
        $user = Auth::user()->id;
        $is_reviewer = Auth::user()->is_reviewer;
        $unit_kerja_id = Auth::user()->unit_kerja_id;
        $unit_kerja_code = Auth::user()->unit_kerja_code;

        $query = Model::where(function ($query) use ($role, $user, $is_reviewer, $unit_kerja_code) {

            $query->where(function ($q) use ($role, $user) {
                $q->where('role_id', $role);
                $q->orWhere('user_id', $user);
            });

            $query->where('status', false);
            $query->where('unit_kerja_code', $unit_kerja_code);
        })
            ->orderBy('created_at', 'desc')->count();

        return $this->outputResult($query);
    }

    public function listData(array $data)
    {
        $role = Auth::user()->roles->pluck('id')->toArray()[0];
        $user = Auth::user()->id;
        $is_reviewer = Auth::user()->is_reviewer;
        $unit_kerja_code = Auth::user()->unit_kerja_code;

        $query = Model::where(function ($query) use ($data, $role, $user, $is_reviewer, $unit_kerja_code) {

            $query->where(function ($q) use ($role, $user) {
                $q->where('role_id', $role);
                $q->orWhere('user_id', $user);
            });


            if ($data['keyword'] != '') {
                $query->whereLike('msg', $data['keyword']);
            }


            $query->where('unit_kerja_code', $unit_kerja_code);
            $query->where('bagian_code', session('userdata')['kodebagian']);
        })->orderBy('created_at', 'desc')->data();

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {

            })
            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->addColumn('msg', function ($query) {
                return '<a class="'. (($query->status == true) ? 'text-muted' : '') .'" href="' . route('mapping.notification.read', ['id' => encrypt($query->id)]) . '" target="_blank">' . $query->msg . '</a>';
            })
            ->addColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->isoFormat('DD MMMM YY hh:mm:ss');
            })
            ->addColumn('checkbox', function ($query) {
                return true;
            })
            ->rawColumns(['msg'])
            ->make(true)
            ->getData(true);
    }

    public static function insert(array $data)
    {
        return Model::createOne([
            'source_id' => $data['source_id'],
            'url' => $data['url'],
            'msg' => $data['msg'],
            'role_id' => !empty($data['role_id']) ? $data['role_id'] : null,
            'user_id' => !empty($data['user_id']) ? $data['user_id'] : null,
            'unit_kerja_id' => !empty($data['unit_kerja_id']) ? $data['unit_kerja_id'] : null,
            'unit_kerja_code' => !empty($data['unit_kerja_code']) ? $data['unit_kerja_code'] : null,
            'status' => false
        ]);
    }

    public static function send(array $data)
    {
        return Model::createOne([
            'source_id' => $data['source_id'],
            'url' => $data['url'],
            'msg' => $data['msg'],
            'role_id' => !empty($data['role_id']) ? $data['role_id'] : null,
            'user_id' => !empty($data['user_id']) ? $data['user_id'] : null,
            'unit_kerja_id' => !empty($data['unit_kerja_id']) ? $data['unit_kerja_id'] : null,
            'unit_kerja_code' => !empty($data['unit_kerja_code']) ? $data['unit_kerja_code'] : null,
            'status' => false
        ], function ($query, $event) use ($data) {
            /* send email notification */
            if (isset($data['user_id'])) {
                $recipient = UserModel::where('id', $data['user_id'])->pluck('email')->toArray();
            }

            if (isset($data['role_id'])) {
                $recipient = UserModel::whereHas('roles', function ($query) use ($data) {
                    $query->where('role_id', $data['role_id']);
                    // $query->where('unit_kerja_id', $data['unit_kerja_id']);
                    $query->where('unit_kerja_code', $data['unit_kerja_code']);
                })->pluck('email')->toArray();
            }
            /* jika recipient ada maka kirim email kalo tidak ketemu jangan dikirim */
            if (isset($recipient)) {
                $data['url'] = route('mapping.notification.read', ['id' => encrypt($event['id'])]);
                try {
                    Mail::to($recipient)->send(new LedReviewMail($data));
                } catch (Exception $e) {
                }
            }
        });
    }

    public static function get($id)
    {
        $query = Model::find($id);
        if ($query) {
            return $query;
        }

        return false;
    }

    public function update(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::updateOne($data['id'], [
                'url' => $data['url'],
                'msg' => $data['msg'],
                'role_id' => $data['role_id'],
                'status' => $data['status'] ? 1 : 0
            ], function ($query, $event, $cursor) use ($data) {
            });
        });
    }

    public function destroy(array $data)
    {
        return Model::deleteOne($data['id']);
    }

    public function destroys(array $data)
    {
        $id = [];
        foreach ($data['id'] as $value) {
            $id[] = decrypt($value);
        }

        return Model::transaction(function () use ($id) {
            return Model::deleteBatch($id);
        });
    }

    public function read($id)
    {
    }

    public static function sendMail(array $data)
    {
        return self::sendMailByRole($data);
    }

    public static function sendMailByRole(array $data)
    {
        AppconfigService::initConfig('MAIL');
        $template = MastertemplateService::getByKode('EMAIL');

        $query = self::getData($data);
        foreach ($query as $key => $value) {
            try {
                $body = view('pages.emails.send', ['mailData' => $value['body']])->render();
                $body = str_replace(['@name', '@body'], [$value['name'], $body], $template);

                $send = Mail::to($key)->send(new NotifikasiMail($body));

                if ($send instanceof SentMessage) {
                    Model::whereIn('id', $value['id'])->update(['send_email' => true]);
                }
            } catch (Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public static function getData(array $data)
    {
        $query = Model::whereNull('user_id')->where('send_email', false)->limit(5)->orderBy('created_at', 'desc')->get();

        $sender = [];
        foreach ($query as $key => $value) {

            /* send email by roles */
            $users = self::getUserByRole($value->role_id, $value->unit_kerja_code);

            foreach ($users as $user) {
                $sender[$user->email]['name'] = $user->name;
                $sender[$user->email]['body'][] = $value;
                $sender[$user->email]['id'][] = $value->id;
            }
        }

        $queryByuserID = Model::with(['users'])->whereNull('role_id')->where('send_email', false)->limit(5)->orderBy('created_at', 'desc')->get();

        foreach ($queryByuserID as $key => $value) {
            if (isset($value->users->email)) {
                $sender[$value->users->email]['name'] = $value->users->name;
                $sender[$value->users->email]['body'][] = $value;
                $sender[$value->users->email]['id'][] = $value->id;
            }
        }

        return $sender;
    }



    public static function getUserByRole($role_id, $unit_kerja_code)
    {
        $query = DB::table('users', 'a')
            ->select([
                'a.email',
                'a.name'
            ])
            ->join('user_has_roles as b', function ($query) use ($role_id) {
                $query->on('b.user_id', '=', 'a.id');
                $query->where('b.role_id', $role_id);
            })
            ->where('unit_kerja_code', $unit_kerja_code)
            ->get();

        return $query;
    }

    public static function data_notif_risktreatment(array $data)
    {
        $config = AppconfigService::getConfig('NOTIF');

        $risktreatment = ModelRiskTreatment::with(['riskregister', 'riskregister.penetapan.rcsamonitoring', 'riskregister.katalogrisiko'])
            ->whereHas('riskregister.penetapan', function ($query) {
                $query->where('status', 'FINAL');
            })
            ->whereRaw("(tgl_target - now()::date) <= " . $config['NOTIF_RISKTREATMENT_BEFORE_TARGET'])
            ->whereNull('status')
            ->limit(10)->get();

        $sender = [];

        foreach ($risktreatment as $key => $value) {
            $users = UserService::ByUnitKerja($value->riskregister->penetapan->unit_kerja_code);
            foreach ($users as $user) {
                $obj = new stdClass();

                $obj->msg = __('Terdapat risk treatment ' . $value->risk_treatment . ' pada isu risiko (' . $value->riskregister->katalogrisiko->code . ') ' . $value->riskregister->katalogrisiko->name . ' pada ' . $value->riskregister->penetapan->rcsamonitoring->name . ' penetapan tahun ' . $value->riskregister->penetapan->tahun);
                $obj->created_at = $value->tgl_target;

                $sender[$user->email]['name'] = $user->name;
                $sender[$user->email]['body'][] = $obj;
            }
        }

        return $sender;
    }

    public static function notif_risktreatment(array $data)
    {
        AppconfigService::initConfig('MAIL');
        $template = MastertemplateService::getByKode('EMAIL');

        $query = self::data_notif_risktreatment($data);

        foreach ($query as $key => $value) {

            $body = view('pages.emails.send', ['mailData' => $value['body']])->render();
            $body = str_replace(['@name', '@body'], [$value['name'], $body], $template);
            try {

                $send = Mail::to($key)->send(new NotifikasiMail($body));
            } catch (Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public static function pushNotif(array $data){
        if (isset ($data['role_id'])) {
            if (is_array($data['role_id'])) {
                foreach ($data['role_id'] as $val) {
                    Model::createOne([
                        'source_id' => $data['source_id'],
                        'url' => $data['url'],
                        'msg' => $data['msg'],
                        'role_id' => $val,
                        'unit_kerja_code' => $data['unit_kerja_code'],
                        'type' => 'R'
                    ]);
                }
            } else {
                return Model::createOne([
                    'source_id' => $data['source_id'],
                    'url' => $data['url'],
                    'msg' => $data['msg'],
                    'role_id' => $data['role_id'],
                    'unit_kerja_code' => $data['unit_kerja_code'],
                    'type' => 'R'
                ]);
            }
        } else {
            return Model::createOne([
                'source_id' => $data['source_id'],
                'url' => $data['url'],
                'msg' => $data['msg'],
                'user_id' => $data['user_id'],
                'unit_kerja_code' => $data['unit_kerja_code'],
            ]);
        }
    }
}
