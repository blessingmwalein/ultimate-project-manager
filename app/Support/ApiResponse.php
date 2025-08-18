<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
	public static function success(mixed $data = null, int $code = 200, array $meta = []): JsonResponse
	{
		$payload = ['success' => true];
		if (! is_null($data)) {
			$payload['data'] = $data;
		}
		if ($meta) {
			$payload['meta'] = $meta;
		}
		return response()->json($payload, $code);
	}

	public static function created(mixed $data = null, array $meta = []): JsonResponse
	{
		return self::success($data, 201, $meta);
	}

	public static function paginated(LengthAwarePaginator $paginator): JsonResponse
	{
		return self::success(
			$paginator->items(),
			200,
			[
				'current_page' => $paginator->currentPage(),
				'per_page' => $paginator->perPage(),
				'total' => $paginator->total(),
				'last_page' => $paginator->lastPage(),
			]
		);
	}

	public static function error(string $message, int $code = 400, array $errors = []): JsonResponse
	{
		$payload = [
			'success' => false,
			'message' => $message,
		];
		if ($errors) {
			$payload['errors'] = $errors;
		}
		return response()->json($payload, $code);
	}
}
