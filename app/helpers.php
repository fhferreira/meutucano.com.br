<?php

if (!function_exists('logMessage')) {
    /**
     * Retorna a mensagem de log formatada
     *
     * @param $data
     * @return int
     */
    function logMessage($exception, $message = 'Erro')
    {
        return sprintf("%s
            Arquivo: %s
            Linha: %s
            Mensagem: %s
            ------------------------
            %s
        ",
            $message,
            $exception ? $exception->getFile() : '',
            $exception ? $exception->getLine() : '',
            $exception ? $exception->getMessage() : '',
            htmlentities($exception->getTraceAsString())
        );
    }
}

if (!function_exists('dataToTimestamp')) {
    /**
     * Format date as timestamp
     *
     * @param $data
     * @return int
     */
    function dataToTimestamp($data)
    {
        $ano = substr($data, 6, 4);
        $mes = substr($data, 3, 2);
        $dia = substr($data, 0, 2);
        return mktime(0, 0, 0, $mes, $dia, $ano);
    }
}

if (!function_exists('Soma1dia')) {
    /**
     * Sum 1 day to timestamp
     *
     * @param $data
     * @return bool|string
     */
    function Soma1dia($data)
    {
        $datetime = DateTime::createFromFormat('d/m/Y', $data);
        date_add($datetime, date_interval_create_from_date_string('1 days'));
        return date_format($datetime, 'd/m/Y');
    }
}

if (!function_exists('SomaDiasUteis')) {
    /**
     * Sum util days to the date
     *
     * @param $xDataInicial
     * @param $xSomarDias
     * @return bool|string
     */
    function SomaDiasUteis($xDataInicial, $xSomarDias)
    {
        $feriados = \Config::get('core.feriados');

        for ($j = 1; $j <= $xSomarDias; $j++) {
            $xDataInicial = Soma1dia($xDataInicial);

            if (date("w", dataToTimestamp($xDataInicial)) == "0") {
                $xSomarDias++;
            } elseif (date("w", dataToTimestamp($xDataInicial)) == "6") {
                $xSomarDias++;
            } elseif (in_array(date("d/m", dataToTimestamp($xDataInicial)), $feriados)) {
                $xSomarDias++;
            }
        }
        return $xDataInicial;
    }
}

if (!function_exists('diasUteisPeriodo')) {
    /**
     * Sum util days to the date
     *
     * @param $dataInicial
     * @param $dataFinal
     * @param bool $apenasPerido
     * @return bool|string
     */
    function diasUteisPeriodo($dataInicial, $dataFinal, $apenasPerido = false)
    {
        $feriados = \Config::get('core.feriados');

        $inicio = DateTime::createFromFormat('d/m/Y', $dataInicial);
        $final  = DateTime::createFromFormat('d/m/Y', $dataFinal);

        $diasTotal = $final->diff($inicio)->days + 1;
        $period = new DatePeriod($inicio, new DateInterval('P1D'), $final);

        $diasMes = [];
        foreach ($period as $datePeriod) {
            if (in_array($datePeriod->format('d/m'), $feriados)) {
                $diasTotal--;
            } elseif (in_array($datePeriod->format('w'), ['0', '6'])) {
                $diasTotal--;
            } elseif ($apenasPerido == false) {
                if (array_key_exists($datePeriod->format('n'), $diasMes)) {
                    $diasMes[$datePeriod->format('n')]++;
                } else {
                    $diasMes[$datePeriod->format('n')] = 1;
                }
            }
        }

        if ($apenasPerido) {
            return $diasTotal;
        } else {
            return ['total' => $diasTotal, 'mes' => $diasMes];
        }
    }
}

/**
 * Envia um e-mail informando o erro para o desenvolvedor
 *
 * @param  string $error a mensagem completa de erro
 * @return void
 */
function reportError($error)
{
    if (getenv('APP_ENV') !== 'testing') {
        \Mail::send('emails.error', [
            'error' => $error
        ], function ($m) {
            $m->from(\Config('core.report_email'), 'Meu Tucano');
            $m->to(\Config('core.report_email'), 'DEV')->subject('Erro no sistema!');
        });
    }
}

if (!function_exists('numbers')) {
    /**
     * Retorna apenas os digitos de uma string
     *
     * @param  string $string
     * @return string
     */
    function numbers($string)
    {
        return preg_replace('/\D/', '', $string);
    }
}

/**
 * Retorna o numero sem ponto e com duas casas após ele
 *
 * @param  string $string
 * @return string
 */
function currencyNumbers($string)
{
    $dot = strpos($string, '.');
    $decimals = str_pad(substr($string, ($dot + 1), 2), 2, '0');
    return (int) (substr($string, 0, $dot) . $decimals);
}

if (!function_exists('getCurrentUserId')) {
    /**
     * Retorna o id do usuário logado
     *
     * @return int
     */
    function getCurrentUserId()
    {
        try {
            return Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate()->id;
        } catch (\Exception $e) {
        }

        return null;
    }
}

if (!function_exists('removeAcentos')) {
    /**
     * Remove acentos de uma string
     *
     * @return int
     */
    function removeAcentos($string)
    {
        $map = array(
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'É' => 'E',
            'Ê' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ú' => 'U',
            'Ü' => 'U',
            'Ç' => 'C'
        );

        return strtr($string, $map);
    }
}

if (!function_exists('hasModule')) {
    /**
     * Check if module is active based in your config file
     *
     * @param  string  $module nome do modulo
     * @return boolean         if module is active
     */
    function hasModule($module)
    {
        return !is_null(\Config::get(str_slug($module)));
    }
}

if (!function_exists('dateConvert')) {
    /**
     * Coverts a date format with Carbon
     *
     * @param  string $date date to convert
     * @param  string $from from format
     * @param  string $to   to format
     * @return string       formated date
     */
    function dateConvert($date = null, $from = 'Y-m-d H:i:s', $to = 'd/m/Y H:i')
    {
      if (!$date) {
          return null;
      }

      return \Carbon\Carbon::createFromFormat($from, $date)->format($to);
    }
}

if (!function_exists('diffForHumans')) {
    /**
     * Return the date difference for humans
     * @param  string $date date to check difference
     * @param  string $from from format
     * @return string       formated date diff for humans
     */
    function diffForHumans($date = null, $from = 'Y-m-d H:i:s') {
        if (!$date) {
            return null;
        }

        \Carbon\Carbon::setLocale(config('app.locale'));

        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
    }
}

if (!function_exists('parseMarketplaceId')) {
    /**
     * Formata o ID do pedido no marketplace
     *
     * @param  string  $marketplace
     * @param  id      $pedidoId
     * @return string
     */
    function parseMarketplaceId($marketplace, $pedidoId)
    {
        if ($marketplace === 'B2W') {
            if (substr($pedidoId, 0, 1) !== '0') {
                $inicio = substr($pedidoId, 0, 2);

                if ($inicio === '10') {
                    $inicioId = '01';
                    $posSub   = 2;
                } else {
                    $inicioId = '0' . substr($pedidoId, 0, 1);
                    $posSub   = 1;
                }

                $fim = substr($pedidoId, $posSub, -2);

                return $inicioId . '-' . $fim;
            }
        } elseif ($marketplace === 'WALMART') {
            if (strpos($pedidoId, '-') > 0) {
                return substr($pedidoId, 0, strpos($pedidoId, '-'));
            }
        }

        return $pedidoId;
    }
}
