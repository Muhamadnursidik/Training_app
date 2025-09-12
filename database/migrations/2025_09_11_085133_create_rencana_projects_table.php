<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_projects', function (Blueprint $table) {
            $table->id();
            
            // Project information
            $table->string('kode_project', 50)->index()->comment('Kode identifikasi project');
            
            // Activity details
            $table->string('aktivitas', 255)->comment('Nama/deskripsi aktivitas');
            $table->unsignedSmallInteger('level')->default(1)->index()->comment('Level hierarki aktivitas (1=root, 2=sub, dst)');
            
            // Hierarchical relationship
            $table->unsignedBigInteger('parent_id')->nullable()->index()->comment('ID parent aktivitas untuk hierarki');
            
            // Weight and progress
            $table->decimal('bobot', 5, 2)->default(0)->comment('Bobot aktivitas dalam persen (0-100)');
            
            // Timeline
            $table->date('tanggal_mulai')->nullable()->index()->comment('Tanggal mulai aktivitas');
            $table->date('tanggal_akhir')->nullable()->index()->comment('Tanggal akhir aktivitas');
            $table->unsignedSmallInteger('minggu_ke')->nullable()->index()->comment('Minggu ke berapa dalam tahun (1-53)');
            
            // Audit trails
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('rencana_projects')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            
            // Indexes for performance
            $table->index(['kode_project', 'level', 'tanggal_mulai'], 'idx_project_level_date');
            $table->index(['tanggal_mulai', 'tanggal_akhir'], 'idx_date_range');
            $table->index(['deleted_at'], 'idx_soft_delete');
            
            // Table options
            $table->comment('Tabel untuk menyimpan rencana project dengan struktur hierarkis');
        });
        
        // Create indexes after table creation for better organization
        Schema::table('rencana_projects', function (Blueprint $table) {
            // Composite index for common queries
            $table->index(['kode_project', 'parent_id'], 'idx_project_parent');
            $table->index(['level', 'bobot'], 'idx_level_bobot');
            $table->index(['minggu_ke', 'tanggal_mulai'], 'idx_week_start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop foreign key constraints first
        Schema::table('rencana_projects', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
        
        // Drop the table
        Schema::dropIfExists('rencana_projects');
    }
};