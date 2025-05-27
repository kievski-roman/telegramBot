<?php
interface BotCommandInterface
{
    public function execute(string $text):string;
}