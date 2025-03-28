<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION update_updated_at_column()
                RETURNS TRIGGER AS
            $$
            BEGIN
                UPDATE requests SET score = (SELECT sum(score) from score_request_indicator sri WHERE sri.request_id = NEW.request_id) WHERE id = NEW.request_id;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Create the trigger
        DB::statement("
            CREATE TRIGGER update_score_updated_at
                AFTER UPDATE
                ON score_request_indicator
                FOR EACH ROW
            EXECUTE FUNCTION update_updated_at_column();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS update_score_updated_at ON score_request_indicator;");
        DB::statement("DROP FUNCTION IF EXISTS update_updated_at_column();");
    }
};
