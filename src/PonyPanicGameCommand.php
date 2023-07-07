<?php

namespace PonyPanic;

use PonyPanic\Enum\GameMode;
use PonyPanic\Service\GameService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('Game:play', 'Starting game process')]
class PonyPanicGameCommand extends Command
{
    private const ARGUMENT_MODE = 'mode';

    private const ARGUMENT_CONTINUE = 'continue';

    private const OPTION_SIZE = 'size';

    private const OPTION_TRESURE_COUNT = 'treasure-count';

    private GameService $gameService;

    public function __construct()
    {
        parent::__construct('play');
        $this->gameService = new GameService();
    }

    /**
     * @return void
     */
    public function configure(): void
    {
        parent::configure();

        $this->addOption(
            self::OPTION_SIZE,
            's',
            InputOption::VALUE_OPTIONAL,
            'Size of the frestyle map (NxN)',
            '5x5'
        );

        $this->addOption(
            self::OPTION_TRESURE_COUNT,
            't',
            InputOption::VALUE_OPTIONAL,
            'Threasure count on the frestyle map',
            2
        );

        $this->addArgument(
            self::ARGUMENT_MODE,
            InputArgument::REQUIRED,
            sprintf(
                'Game mode [%s, %s]',
                GameMode::STORY->value,
                GameMode::FREESTYLE->value
            )
        );

        $this->addArgument(
            self::ARGUMENT_CONTINUE,
            InputArgument::OPTIONAL,
            'Continue story from the last level',
            false
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $mode     = $input->getArgument(self::ARGUMENT_MODE);
        $continue = (bool)$input->getArgument(self::ARGUMENT_CONTINUE);

        [$height, $width] = explode('x', $input->getOption(self::OPTION_SIZE));
        $treasureCount = (int)$input->getOption(self::OPTION_TRESURE_COUNT);

        try {
            $game = GameMode::isStory($mode)
                ? $this->gameService->story($continue)
                : $this->gameService->freestyle([(int)$height, (int)$width, $treasureCount]);

            $output->writeln(
                sprintf(
                    'Starting level %s. Need to get %s treasures.',
                    $game->getCurrentLevel(),
                    count($game->getCurrentMap()->getTreasures())
                )
            );

            while (true) {
                $logs = $this->gameService->makeATurn();
                if (!empty($logs)) {
                    $output->writeln($logs);
                }

                sleep(0.5); //Prevent ddos and connection refused errors

                if ($game->isMapLost()) {
                    $this->gameService->nextLevel(reset: true);
                    $output->writeln('Retrying level ' . $game->getCurrentLevel());
                }

                if ($game->isMapWon()) {
                    if (GameMode::isStory($game->getMode())) {
                        $this->gameService->nextLevel();
                        $output->writeln('Starting level ' . $game->getCurrentLevel());
                        continue;
                    }

                    $output->writeln('Frestyle Map won!');
                    break;
                }
            }
        } catch (\Throwable $exception) {
            $output->writeln(
                sprintf('Error occurred: %s', $exception->getMessage())
            );
        }

        return 0;
    }
}