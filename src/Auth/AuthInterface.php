<?php
namespace SwedbankJson\Auth;

/**
 * Interface AuthInterface
 * @package SwedbankJson\Auth
 */
interface AuthInterface
{
    /**
     * Inled inloggon
     *
     * @return bool Om inloggingen lyckades eller ej
     */
    public function login();
}