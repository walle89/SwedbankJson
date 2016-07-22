<?php
namespace SwedbankJson\Auth;

/**
 * Interface AuthInterface
 * @package SwedbankJson\Auth
 */
interface AuthInterface
{
    /**
     * Sign in
     *
     * @return bool True for successful sign in
     */
    public function login();
}