<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workspace_invitations', function (Blueprint $table) {
            // Drop the existing user_id foreign key constraint
            $table->dropForeign(['user_id']);
            $table->dropIndex('unique_workspace_user_invitation');
            
            // Rename user_id to invitee_id and add invitee_type for polymorphic relationship
            $table->renameColumn('user_id', 'invitee_id');
            $table->string('invitee_type')->after('invitee_id'); // Will be 'App\Models\User' or 'App\Models\Company'
            
            // Add new unique constraint for polymorphic relationship
            $table->unique(['workspace_id', 'invitee_id', 'invitee_type'], 'unique_workspace_invitee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_invitations', function (Blueprint $table) {
            // Drop the polymorphic setup
            $table->dropUnique('unique_workspace_invitee');
            $table->dropColumn('invitee_type');
            
            // Rename back to user_id
            $table->renameColumn('invitee_id', 'user_id');
            
            // Restore the original foreign key and unique constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['workspace_id', 'user_id'], 'unique_workspace_user_invitation');
        });
    }
};
