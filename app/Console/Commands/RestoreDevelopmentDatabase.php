<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestoreDevelopmentDatabase extends Command
{
    protected $signature = 'db:restore-dev
                            {--force : Executa sem confirmação interativa}';

    protected $description = 'Recria o banco de desenvolvimento e executa os seeders locais';

    public function handle(): int
    {
        if (app()->environment('production')) {
            $this->error('Este comando não pode ser executado em produção.');

            return self::FAILURE;
        }

        $database = config('database.connections.'.config('database.default').'.database');

        if (! $this->option('force') && ! $this->confirm(
            "Isso apagará todos os dados do banco \"{$database}\" e executará migrate:fresh --seed. Deseja continuar?"
        )) {
            $this->info('Operação cancelada.');

            return self::SUCCESS;
        }

        $this->info("Restaurando banco \"{$database}\"...");

        $this->call('migrate:fresh', [
            '--seed'  => true,
            '--force' => true,
        ]);

        $this->newLine();
        $this->info('Banco restaurado com sucesso.');
        $this->line('Usuários disponíveis:');
        $this->line('  • admin@ltfin.test / password (admin)');
        $this->line('  • rjtheodoro@gmail.com / 1234 (admin)');
        $this->line('  • demo@demo.com / demo');

        return self::SUCCESS;
    }
}
