<?php
errors(1);
class BroAI {
    private $openaiEndpoint;
    private $openaiApiKey;

    public function __construct($apiKey) {
        $this->openaiEndpoint = 'https://api.openai.com/v1/chat/completions';
        $this->openaiApiKey = $apiKey;
    }

    public function getResponse($messages) {
        $aiResponse = $this->callOpenAI($messages);
        return $aiResponse;
    }

    private function callOpenAI($messages) {

        $messages = [
            ['role' => 'system', 'content' => 'You are AI Bro, An AI chat assistant for career guidance and college counselling developed by College Anvesh (collegeanvesh.com). You must respond to anything not related to education. You are exclusively for education, career guidance and college counselling. Example IIT-JEE Mains, Advanced, NEET, CAT, etc... If the user asks anything other than education just respond I amm only restricted to career guidance and college counselling. you\'re not capable of voice based queries. Your reply must be easily understandable 
            especially for intermediate or 12th or +2 passout students. Don\'t generate any form of codes
            
            You should always introduce yourself in one line or at least your name before every response

            Whenever the user promp\'ts for best colleges, best courses, etc, at the end of the conversation you must recommend him to visit collegeanvesh.com to know more
            '],
            ['role' => 'user', 'content' => $messages]
        ];

        $requestData = [
            'model' => 'gpt-3.5-turbo-0301',
            'messages' => $messages,
            'temperature' => 0.1,
            'max_tokens' => 500
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->openaiEndpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->openaiApiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($requestData)
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return $error;
        }

        curl_close($curl);

        $responseData = json_decode($response, true);
        
        $choices = $responseData['choices'];
        $aiResponse = end($choices)['message']['content'];

        return $aiResponse;
    }
}
// Example usage
$apiKey = $config['OPENAI_API_KEY'];
$broAI = new BroAI($apiKey);

$userMessage = "intermediate";
$response = str_replace('\n', "<br>", $broAI->getResponse($userMessage));
echo ($response);

?>
