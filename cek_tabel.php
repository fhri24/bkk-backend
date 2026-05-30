<?php
$tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname='public'");
foreach ($tables as $table) {
    echo $table->tablename . "\n";
}
