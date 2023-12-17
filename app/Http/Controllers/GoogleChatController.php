<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;

class GoogleChatController extends Controller {
	public function googleChatReq(Request $request) {
		try {
			$client = new Client();
			$messages = $request->messages;

			$prompt = [
				'context' => `Act like A CHAT BOT for INSPIZY DEVS COMPANY`,
				'examples' => [],
				'messages' => $messages,
			];

			$apiKey = config('app.maker_suit_api_key');
			$modelName = 'chat-bison-001';
			$temperature = 1;
			$candidateCount = 1;
			$topK = 40;
			$topP = 0.95;

			$client = new Client();

			$response = $client->post("https://generativelanguage.googleapis.com/v1beta2/models/$modelName:generateMessage", [
				RequestOptions::HEADERS => [
					'Content-Type' => 'application/json',
				],
				RequestOptions::JSON => [
					'prompt' => $prompt,
					'temperature' => $temperature,
					'top_k' => $topK,
					'top_p' => $topP,
					'candidate_count' => $candidateCount,
				],
				RequestOptions::QUERY => [
					'key' => $apiKey,
				],
				// RequestOptions::VERIFY => 'C:\wamp64\bin\php\php8.0.25\extras\ssl\cacert.pem', // Specify the path to the cacert.pem file

			]);

			$data = json_decode($response->getBody(), true);
			return response()->json(['status' => true, 'chat' => $data]);
		} catch (\Throwable $th) {
			return response()->json(['status' => false, 'message' => $th->getMessage(), 'line' => $th->getLine(), 'file' => $th->getFile()]);

		}
		// Do something with the generated text
	}
}
