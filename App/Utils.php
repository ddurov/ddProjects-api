<?php

namespace Api;

class Utils
{
    /**
     * Возвращает массив с доменами и хэшаши их сертификатов (в случае ошибки, message: domain is invalid)
     * @param string $domainList
     * @return array
     */
    public function getPinningHashDomains(string $domainList): array
    {
        $preparedData = [];

        $domainList = explode(",", $domainList);

        for ($i = 0; $i < count($domainList); $i++) {
            if (gethostbyname($domainList[$i]) === $domainList[$i]) {
                $preparedData[] = ["domain" => $domainList[$i], "requestStatus" => "error", "message" => "domain is invalid"];
                continue;
            }
            $preparedData[] = ["domain" => $domainList[$i], "requestStatus" => "ok", "hash" => shell_exec("openssl s_client -verify_quiet -connect ".htmlspecialchars($domainList[$i], ENT_QUOTES).":443 | openssl x509 -pubkey -noout | openssl pkey -pubin -outform der | openssl dgst -sha256 -binary | openssl enc -base64")];
        }

        return $preparedData;
    }
}