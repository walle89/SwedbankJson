<?php
/**
 * Created by PhpStorm.
 * User: Eric Wallmander
 * Date: 15-02-28
 * Time: 20:19
 */

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