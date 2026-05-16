<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('support_messages')) {
            Schema::table('support_messages', function (Blueprint $table) {
                if (! Schema::hasColumn('support_messages', 'attachment_path')) {
                    $table->string('attachment_path')->nullable()->after('message');
                }

                if (! Schema::hasColumn('support_messages', 'attachment_original_name')) {
                    $table->string('attachment_original_name')->nullable()->after('attachment_path');
                }

                if (! Schema::hasColumn('support_messages', 'attachment_mime_type')) {
                    $table->string('attachment_mime_type')->nullable()->after('attachment_original_name');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('support_messages')) {
            Schema::table('support_messages', function (Blueprint $table) {
                if (Schema::hasColumn('support_messages', 'attachment_mime_type')) {
                    $table->dropColumn('attachment_mime_type');
                }

                if (Schema::hasColumn('support_messages', 'attachment_original_name')) {
                    $table->dropColumn('attachment_original_name');
                }

                if (Schema::hasColumn('support_messages', 'attachment_path')) {
                    $table->dropColumn('attachment_path');
                }
            });
        }
    }
};