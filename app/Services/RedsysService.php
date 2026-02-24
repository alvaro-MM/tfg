<?php

namespace App\Services;

class RedsysService
{
    /**
     * Genera los campos del formulario para redirigir a Redsys.
     *
     * @param array<string, mixed> $merchantParams
     * @return array{signatureVersion: string, merchantParameters: string, signature: string}
     */
    public function buildFormFields(array $merchantParams): array
    {
        $merchantParameters = $this->encodeMerchantParameters($merchantParams);
        $order = (string) ($merchantParams['DS_MERCHANT_ORDER'] ?? '');

        return [
            'signatureVersion' => (string) config('redsys.signature_version', 'HMAC_SHA256_V1'),
            'merchantParameters' => $merchantParameters,
            'signature' => $this->createMerchantSignature($merchantParameters, $order),
        ];
    }

    /**
     * Verifica la firma de una respuesta de Redsys.
     */
    public function verifyResponseSignature(string $merchantParametersBase64, string $signature): bool
    {
        $params = $this->decodeMerchantParameters($merchantParametersBase64);
        $order = (string) ($params['Ds_Order'] ?? '');

        if ($order === '') {
            return false;
        }

        $expected = $this->createMerchantSignature($merchantParametersBase64, $order);

        return hash_equals($expected, $signature);
    }

    /**
     * @return array<string, mixed>
     */
    public function decodeMerchantParameters(string $merchantParametersBase64): array
    {
        $json = base64_decode($merchantParametersBase64, true);
        if ($json === false) {
            return [];
        }

        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param array<string, mixed> $params
     */
    public function encodeMerchantParameters(array $params): string
    {
        $json = json_encode($params, JSON_UNESCAPED_SLASHES);
        return base64_encode($json ?: '{}');
    }

    /**
     * Crea firma HMAC SHA256 V1 (Redsys).
     */
    private function createMerchantSignature(string $merchantParametersBase64, string $order): string
    {
        $secretKeyB64 = (string) config('redsys.key', '');
        $secretKey = base64_decode($secretKeyB64, true);
        if ($secretKey === false) {
            $secretKey = '';
        }

        $key = $this->encrypt3Des($order, $secretKey);
        $hmac = hash_hmac('sha256', $merchantParametersBase64, $key, true);

        return base64_encode($hmac);
    }

    /**
     * Cifra el número de pedido con 3DES tal como exige Redsys.
     */
    private function encrypt3Des(string $message, string $key): string
    {
        $iv = "\0\0\0\0\0\0\0\0";
        $padded = $this->zeroPad($message, 8);

        $encrypted = openssl_encrypt($padded, 'des-ede3-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

        return $encrypted === false ? '' : $encrypted;
    }

    private function zeroPad(string $text, int $blockSize): string
    {
        $pad = $blockSize - (strlen($text) % $blockSize);
        if ($pad === $blockSize) {
            $pad = 0;
        }
        return $text . str_repeat("\0", $pad);
    }
}

