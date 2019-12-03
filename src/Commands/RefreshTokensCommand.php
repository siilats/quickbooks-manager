<?php

namespace Hotrush\QuickBooksManager\Commands;

use Hotrush\QuickBooksManager\QuickBooksManager;
use Hotrush\QuickBooksManager\QuickBooksToken;
use Illuminate\Console\Command;
use QuickBooksOnline\API\Exception\ServiceException;

class RefreshTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qbm:refresh-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh access tokens.';

    /**
     * Execute the console command.
     *
     * @param QuickBooksManager $manager
     *
     * @return mixed
     */
    public function handle(QuickBooksManager $manager)
    {
        $tokens = QuickBooksToken::where('refresh_at','<=',now())->where('refresh_expire_at','>',now())->get();

        if (!$tokens->count()) {
            return;
        }

        foreach ($tokens as $token) {
            try {
                $manager->connection($token->connection)->refreshToken();
            } catch (ServiceException $e) {
                $this->error(sprintf('Error refreshing token for connection "%s": %s', $token->connection, $e->getMessage()));
            }
        }
    }
}