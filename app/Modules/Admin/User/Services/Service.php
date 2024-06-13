<?php
namespace App\Modules\Admin\User;

use App\Bases\BaseService;
use DataTables;
use App\Modules\Admin\User\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Modules\Admin\Role\Model as Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Modules\Master\Unitkerja\Service as UnitkerjaService;

class Service extends BaseService
{

    public function __construct()
    {
    }

    public function data(array $data)
    {
        $query = Model::with(['unitkerja'])->select(['users.*','b.blokir'])
                        ->leftJoin('t_block_login as b', function($query) use ($data) {
                            $query->on(DB::raw('LOWER(users.username)'), '=', DB::raw('LOWER(b.key)'));
                        })
                        ->data();
        $query->where('type', 1);

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {

                if ($data['name'] != '') {
                    $query->where(function ($q) use ($data){
                        $q->whereLike('name', $data['name'])->orWhere('username', 'like', '%' . $data['name'] . '%');
                    });
                    // $query->whereLike('name', $data['name'])
                    //     ->orWhere('username', 'like', '%' . $data['name'] . '%')
                    //     ->orWhere('email', 'like', '%' . $data['name'] . '%');
                }

                if ($data['unit_kerja_code'] != '') {
                    $query->where('unit_kerja_code', $data['unit_kerja_code']);
                }

                if ($data['roles'] != '') {
                    $query->whereHas('roles', function ($q) use ($data) {
                        $q->where('id', $data['roles']);
                    });
                }

                if ($data['status'] != '') {
                    if ($data['status'] == 'deleted') {
                        $query->whereNotNull('deleted_at');
                    } else {
                        $query->where('status', $data['status'])->whereNull('deleted_at');
                    }
                }
            })
            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->addColumn('roles', function ($query) {
                $roles = [];
                foreach ($query->roles as $role) {
                    $roles[] = $role->name;
                }
                return $roles;
            })
            ->make(true)
            ->getData(true);
    }

    public function store(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::createOne([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password_created' => Carbon::now(),
                'is_admin' => ($data['is_admin']) ? $data['is_admin'] : false,
                'is_reviewer' => ($data['is_reviewer']) ? $data['is_reviewer'] : false,
                'unit_kerja_code' => $data['unit_kerja_code'],
                'password' => Hash::make($data['password']),
                'view_all_unit' => $data['view_all_unit'] ? 1 : 0,
                'status' => $data['status'] ? 1 : 0,
                'type' => 1,
                'email_verified_at' => now()
            ], function ($query, $event) use ($data) {
                $user = User::find($event->id);
                $user->assignRole(is_array($data['roles']) ? $data['roles'] : []);
            });
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
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'is_admin' => ($data['is_admin']) ? $data['is_admin'] : false,
                'is_reviewer' => ($data['is_reviewer']) ? $data['is_reviewer'] : false,
                'unit_kerja_code' => $data['unit_kerja_code'],
                'view_all_unit' => $data['view_all_unit'] ? 1 : 0,
                'status' => $data['status'] ? 1 : 0
            ], function ($query, $event, $cursor) use ($data) {
                $user = User::find($cursor->id);
                $user->syncRoles(is_array($data['roles']) ? $data['roles'] : []);
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

    public static function roleOptions()
    {
        $roles = [];
        $cursors = Role::isActive()->get();

        foreach ($cursors as $cursor) {
            $roles[$cursor->name] = $cursor->name;
        }

        return $roles;
    }

    public static function getRoles($id)
    {
        $user = Model::find($id);
        $roles = [];

        if ($user) {
            foreach ($user->roles as $role) {
                $roles[] = $role->name;
            }
        }

        return $roles;
    }

    public function changePassword(array $data)
    {
        $data['id'] = Auth::user()->id;
        return Model::transaction(function () use ($data) {
            return Model::updateOne($data['id'], [
                'password' => Hash::make($data['password']),
                'password_created' => Carbon::now(),
                'reset_password' => false,
            ], function ($query, $event, $cursor) use ($data) {

            });
        });
    }

    public function resetPassword(array $data)
    {

        $user = Model::find(decrypt($data['id']));

        return Model::transaction(function () use ($data, $user) {
            return Model::updateOne($user->id, [
                'password' => Hash::make($user->username),
                'password_created' => Carbon::now(),
                'reset_password' => true,
            ], function ($query, $event, $cursor) use ($data) { });
        });
    }

    public static function dropdown($default = '')
    {
        $data = Model::get();
        $results = [];

        if (!is_null($default)) {
            $results[''] = empty($default) ? __('Pilih') : __($default);
        }

        foreach ($data as $user) {
            $results[$user->id] = $user->name;
        }

        return $results;
    }

    public static function dropdown_username_nama($default = '')
    {
        $data = Model::get();
        $results = [];

        if (!is_null($default)) {
            $results[''] = empty($default) ? __('Pilih') : __($default);
        }

        foreach ($data as $user) {
            $results[$user->id] = $user->username . ' - ' . $user->name;
        }

        return $results;
    }

    public static function dropdown_by_role_unit_kerja($default = '', array $data)
    {
        $users = Model::where(function ($query) use ($data) {
            $query->whereHas('roles', function ($q) use ($data) {
                $q->where('id', $data['role_id']);
            });
            $query->whereHas('unitkerja', function ($q) use ($data) {
                $q->where('code', $data['unit_kerja_code']);
            });
            $query->whereNot('id', $data['user_id']);
        })
        ->isActive()
        ->orderBy('name','asc')
        ->get();
        
        $results = [];

        if (!is_null($default)) {
            $results[''] = empty($default) ? __('Pilih') : __($default);
        }

        foreach ($users as $user) {
            $results[$user->id] = $user->name . ' ['.$user->unitkerja->name.']';
        }

        return $results;
    }

    public static function ByUnitKerja($code)
    {
        return Model::where('unit_kerja_code', $code)->isActive()->get();
    }

    public function checkUserBlockedByIp(array $data)
    {
        return Blocklogin::where(function ($query) use ($data) {
            $query->where('key', $data['key']);
            $query->where('ip_address', $data['ip_address']);
            $query->where('blokir', true);
        })->count();
    }

    public function checkUserIsLogin(array $data)
    {
        $user = DB::table('users', 'a')
            ->join('sessions as b', function ($query) {
                $query->on('b.user_id', '=', 'a.id');
            })
            ->where(function ($query) use ($data) {
                $query->whereRaw("(to_timestamp(b.last_activity) + (" . config('session.lifetime') . " ||' minutes')::interval) > now()");
                $query->where('a.username', $data['key']);
                $query->where('a.is_admin', false);
            })
            ->count();

        return ($user) ? true : false;
    }

    public function release(array $data)
    {
        // $user = Model::find(decrypt($data['id']));

        // return Model::transaction(function () use ($data, $user) {
        //     return Model::updateOne($user->id, [
        //         'is_login' => false,
        //     ], function ($query, $event, $cursor) use ($data, $user) {
        //         DB::table('sessions')->where('user_id', $user->id)->delete();
        //     });
        // });
        $user = $this->get(decrypt($data['id']));

        return Blocklogin::where('key', strtolower($user->username))->update(['blokir' => false]);
    }

    public function importUser(array $data)
    {
        $file = $data['files']->getRealPath();

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();

        $header = [
            'no',
            'nama',
            'email',
            'username',
            'password',
            'peran',
            'unit kerja',
            'sebagai admin',
            'sebagai reviewer',
            'status',
        ];

        $col = 'A';
        foreach ($header as $key => $value) {
            if ($value != strtolower($sheet->getCell($col."1"))) {
                return [
                    'code'    => 500,
                    'status'  => 'error',
                    'message' => __('Proses simpan gagal. Format Tidak Sesuai'),
                    'data'    => null
                ];
            }
            $col++;
        }

        for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {

            try {

                $unit_kerja_code =  UnitkerjaService::byName($sheet->getCell("G$i")->getValue());
                $roleName = $sheet->getCell("F$i")->getValue();

                $input = [];
                $cond['username'] = $sheet->getCell("D$i")->getValue();

                $input['name'] = $sheet->getCell("B$i")->getValue();
                $input['email'] = $sheet->getCell("C$i")->getValue();
                $input['password'] = Hash::make($sheet->getCell("E$i")->getValue());
                $input['unit_kerja_code'] = $unit_kerja_code;
                $input['is_admin'] = ($sheet->getCell("H$i")->getValue() == 't') ? 1 : 0;
                $input['is_reviewer'] = ($sheet->getCell("I$i")->getValue() == 't') ? 1 : 0;
                $input['status'] = ($sheet->getCell("J$i")->getValue() == 't') ? 1 : 0;
                $input['type']  = 1;     

                Model::updateOrCreateOne($cond, $input, function ($query, $event) use ($roleName) {
                    $user = User::find($event->id);
                    $user->assignRole($roleName);
                });

            } catch (\Exception $e) {
                return $this->outputResult(['files' => 'in row '.$i. ' data '. $sheet->getCell("B$i")->getValue() . ' : ' .$e->getMessage()],400, 'error');
            }
        }
    }
    
    public function setLockedUser(array $data)
    {
        return Blocklogin::transaction(function () use ($data) {
            return Blocklogin::updateOrCreateOne([
                'key'           => $data['key'],
                'ip_address'    => $data['ip_address']
            ], [
                'blokir'        => true
            ]);
        });
    }
}
