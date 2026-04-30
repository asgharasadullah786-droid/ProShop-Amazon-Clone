<?php

/**
 * @OA\Info(
 *     title="ProShop API",
 *     version="1.0.0",
 *     description="ProShop E-commerce Platform API Documentation"
 * )
 * 
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Local Development Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your bearer token here"
 * )
 */