<?php
namespace Classes\Helper;

class API
{
    public static function exibir(int $ID, string $Modulo, string $Token = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::URI($Modulo, $ID),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return array(
                "Erro" => $err
            );
        } else {
            return json_decode($response, true);
        }
    }

    public static function pesquisar(array $Parametros, string $Modulo, string $Token = null)
    {
        $Parametros = self::verificarParametros($Parametros);

        $URI = self::URI($Modulo) . '/pesquisar?' . http_build_query($Parametros);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $URI,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return array(
                "Erro" => $err
            );
        } else {
            return json_decode($response, true);
        }
    }

    public static function listar(string $Modulo, string $Token = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::URI($Modulo),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return array(
                "Erro" => $err
            );
        } else {
            return json_decode($response, true);
        }
    }

    public static function cadastrar(array $Parametros, string $Modulo, string $Token = null)
    {
        $Parametros = self::verificarParametros($Parametros);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::URI($Modulo),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($Parametros),
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return array(
                "Erro" => $err
            );
        } else {
            return json_decode($response, true);
        }
    }

    public static function alterar(int $ID, array $Parametros, string $Modulo, string $Token = null)
    {
        $Parametros = self::verificarParametros($Parametros);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::URI($Modulo, $ID),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($Parametros),
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return array(
                "Erro" => $err
            );
        } else {
            return json_decode($response, true);
        }
    }

    private static function URI(string $Modulo, int $ID = null)
    {
        $URI = API_URL;

        switch ($Modulo) {
            case 'Unidade-Empregados':
                $URI .= '/unidade/empregados';
                break;
            case 'Unidade-Funcoes':
                $URI .= '/unidade/parametros/funcoes';
                break;
            case 'Unidade-UnidadesCaixa':
                $URI .= '/unidade/unidades';
                break;

            case 'Unidade-Supervisoes':
                $URI .= '/unidade/parametros/supervisoes';
                break;

            case 'Unidade-Coordenacoes':
                $URI .= '/unidade/parametros/coordenacoes';
                break;

            case 'SIGA-SegmentosOperacionais':
                $URI .= '/siga/parametros/segmentos-operacionais';
                break;

            case 'SIGA-Produtos':
                $URI .= '/siga/parametros/produtos';
                break;

            case 'SIGA-Contratos':
                $URI .= '/siga/contratos';
                break;

            case 'SIGA-Clientes':
                $URI .= '/siga/clientes';
                break;

            case 'SIGA-UnidadesCaixa':
                $URI .= '/siga/parametros/unidades';
                break;

            default:
                $URI = '';
        }

        if ($ID === null) {
            return $URI;
        }

        return $URI . '/' . $ID;
    }

    /**
     * Opções de tipo: json ou xml
     *
     * @param string $Tipo
     *
     */
    private static function setTipo(string $Tipo): void
    {
        switch ($Tipo) {
            case 'json':
                $Header = 'content-type: application/json';
                break;
            case 'xml':
                $Header = 'content-type: application/xml; charset=utf8';
                break;
            default:
                $Header = 'content-type: text/plain; charset=utf8';
                break;
        }

        self::$Header = $Header;
    }

    private static function verificarParametros(array $Parametros)
    {
        foreach ($Parametros as $key => $value) {
            if (trim($value) === '') {
                $Parametros[$key] = null;
            }
        }

        return $Parametros;
    }

    /**
     *
     * @param string $Token
     */
    private static function setToken(string $Token): void
    {
        self::$Token = $Token;
    }
}
