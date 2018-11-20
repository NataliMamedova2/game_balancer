<?php

namespace App\Interfaces;

interface CharacterInterface
{
    /**
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * @return int|null
     */
    public function getAttack(): ?int;

    /**
     * @return int|null
     */
    public function getAbilityAttack(): ?int;

    /**
     * @return int|null
     */
    public function getDefence(): ?int;

    /**
     * @return int|null
     */
    public function getAbilityDefence(): ?int;
}
