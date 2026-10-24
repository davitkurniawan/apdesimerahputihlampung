<?php
/**
 * Vercel Serverless Entry Point
 * Routes incoming serverless HTTP requests to root index.php
 */
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/../index.php';
