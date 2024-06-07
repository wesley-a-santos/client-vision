<?php
namespace Classes\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 *
 * @author c068442
 *        
 */
class GeraExcel
{

    private $Cabecalho;

    private $Conteudo;

    private $Arquivo;

    private $SpreadSheet;

    private $ActiveSheet;

    private $Writer;

    function __construct()
    {
        $this->SpreadSheet = new Spreadsheet();
        $this->Writer = new Xlsx($this->SpreadSheet);
        $this->ActiveSheet = $this->SpreadSheet->getActiveSheet();
        $this->setSize();
    }

    /**
     *
     * @param string $Arquivo
     */
    public function setArquivo($Arquivo)
    {
        $this->Arquivo = $Arquivo;
    }

    /**
     *
     * @param string $Planilha
     */
    public function setPlanilha($Planilha = null)
    {
        if ($Planilha != null) {
            $this->ActiveSheet->setTitle($Planilha);
        }
    }

    /**
     *
     * @param array $Cabecalho
     */
    public function setCabecalho($Cabecalho)
    {
        $this->ActiveSheet->fromArray($Cabecalho, '', 'A1');
    }

    /**
     *
     * @param array $Conteudo
     */
    public function setConteudo($Conteudo)
    {
        $this->ActiveSheet->fromArray($Conteudo, '', 'A2');
    }

    public function setFormatoData($Range)
    {
        $this->SpreadSheet->getActiveSheet()
            ->getStyle($Range)
            ->getNumberFormat()
            ->setFormatCode('dd/mm/yyyy');
    }

    public function setFormatoNumero($Range)
    {
        $this->SpreadSheet->getActiveSheet()
            ->getStyle($Range)
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
    }

    private function setSize()
    {
        foreach (range('A', 'Z') as $Coluna) {
            $this->SpreadSheet->getActiveSheet()
                ->getColumnDimension($Coluna)
                ->setAutoSize(true);
        }
    }

    public function Download()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->Arquivo . '"');
        header('Cache-Control: max-age=0');
//        header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $this->Writer->save('php://output');
    }

    public function Salvar()
    {
        $this->Writer->save($this->Arquivo);
    }
}
// $Excel = new \Excel\GeraExcel();
// $Excel->setArquivo("Controle_Contratacao.xlsx");
// $Excel->setPlanilha("Controle");
// $Excel->setCabecalho(array(
//     "CHB",
//     "Classificação",
//     "Status Imóvel",
//     "Status Contratação",
//     "Status Dossiê",
//     "Data Proposta",
//     "Valor Proposta",
//     "Valor Recebido"
// ));
// //$Excel->setConteudo($Contratacao->getResultado());
// $Excel->setConteudo(array(
//     "CHB",
//     "Classificação",
//     "Status Imóvel",
//     "Status Contratação",
//     "Status Dossiê",
//     "Data Proposta",
//     "Valor Proposta",
//     "Valor Recebido"
// ));
// $Excel->Download();