<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_invitation_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();
            $table->string('sender_email');
            $table->string('sender_name')->nullable();
            $table->string('subject');
            $table->enum('status', ['pending', 'sent', 'failed', 'bounced', 'delivered', 'opened', 'clicked'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->text('error_message')->nullable();
            $table->string('ses_message_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['status', 'sent_at']);
            $table->index('recipient_email');
            $table->index('ses_message_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
};
