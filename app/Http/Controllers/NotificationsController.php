<?php

namespace App\Http\Controllers;

use App\Support\ApiResponse;

class NotificationsController extends Controller
{
	public function __construct() { $this->middleware(['auth:sanctum']); }

	public function index()
	{ $user = auth()->user(); return ApiResponse::success($user->notifications()->limit(50)->get()); }

	public function markAllRead()
	{ $user = auth()->user(); $user->unreadNotifications->markAsRead(); return ApiResponse::success(['marked' => true]); }
}


