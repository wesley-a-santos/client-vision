<?php //
//namespace Excel;
//
//
//require_once __DIR__ .  '/../../vendor/autoload.php';
//
///**
// * Description of excell
// *
// * @author c068442
// */
//class Excel {
//
//    //put your code here
//    private $Arquivo;
//    private $Planilha;
//
//    /**
//     * @param mixed $Planilha
//     */
//    public function setArquivo($Arquivo) {
//        $this->Arquivo = $Arquivo;
//    }
//
//    public function setPlanilha($Planilha) {
//        $this->Planilha = $Planilha;
//    }
//
//    private function Ler() {
//        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->Arquivo);
//        //return $spreadsheet->getSheetByName($this->Planilha);
//        return $spreadsheet->getActiveSheet();
//    }
//
//    public function Importar() {
//        $Query = "INSERT INTO dbo.TMP_TBL_001_ANALITICO_VENDAS_IMPORTAR (N ,GILIE ,NU_BEM ,CLASSIFICACAO ,STATUS ,DATA_ALTERACAO_STATUS ,TIPO_VENDA ,DATA_PROPOSTA ,VALOR_RECURSOS_PROPRIOS_PROPOSTA ,VALOR_FGTS_PROPOSTA ,VALOR_FINANCIADO_PROPOSTA ,VALOR_PARCELADO_PROPOSTA ,VALOR_TOTAL_PROPOSTA ,VALOR_TOTAL_CONTRATO ,VALOR_TOTAL_RECEBIDO ,DATA_ULTIMO_RECEBIMENTO ,PV_RECEBIMENTO ,NO_AGENCIA_CONTRATACAO ,VENCIMENTO_PP15)";
//        $Query .= " VALUES (:N ,:GILIE ,:NU_BEM,:CLASSIFICACAO ,:STATUS,:DATA_ALTERACAO_STATUS  ,:TIPO_VENDA ,:DATA_PROPOSTA ,:VALOR_RECURSOS_PROPRIOS_PROPOSTA ,:VALOR_FGTS_PROPOSTA ,:VALOR_FINANCIADO_PROPOSTA ,:VALOR_PARCELADO_PROPOSTA ,:VALOR_TOTAL_PROPOSTA ,:VALOR_TOTAL_CONTRATO ,:VALOR_TOTAL_RECEBIDO ,:DATA_ULTIMO_RECEBIMENTO ,:PV_RECEBIMENTO ,:NO_AGENCIA_CONTRATACAO ,:VENCIMENTO_PP15)";
//
//        $Inserir = new \BancoDeDados\Insert($Query);
//        $Variaveis = new \Geral\Variaveis();
//        $worksheet = $this->Ler();
//
//        $highestRow = $worksheet->getHighestRow(); // e.g. 10
//
//        for ($row = 5; $row <= $highestRow; ++$row) {
//
//            $worksheet->getStyleByColumnAndRow(6, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD2);
//            $worksheet->getStyleByColumnAndRow(8, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD2);
//            $worksheet->getStyleByColumnAndRow(16, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD2);
//            $worksheet->getStyleByColumnAndRow(19, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD2);
//
//            $worksheet->getStyleByColumnAndRow(9, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
//            $worksheet->getStyleByColumnAndRow(10, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
//            $worksheet->getStyleByColumnAndRow(11, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
//            $worksheet->getStyleByColumnAndRow(12, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
//            $worksheet->getStyleByColumnAndRow(13, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
//            $worksheet->getStyleByColumnAndRow(14, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
//            $worksheet->getStyleByColumnAndRow(15, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
//
//            $Inserir->setParametros("N", $worksheet->getCellByColumnAndRow(1, $row)->getValue(), "INT");
//            $Inserir->setParametros("GILIE", $worksheet->getCellByColumnAndRow(2, $row)->getValue(), "CHAR");
//            $Inserir->setParametros("NU_BEM", $worksheet->getCellByColumnAndRow(3, $row)->getValue(), "VARCHAR");
//            $Inserir->setParametros("CLASSIFICACAO", $worksheet->getCellByColumnAndRow(4, $row)->getValue(), "VARCHAR");
//            $Inserir->setParametros("STATUS", $worksheet->getCellByColumnAndRow(5, $row)->getValue(), "VARCHAR");
//            $Inserir->setParametros("DATA_ALTERACAO_STATUS", $Variaveis->FormatarData($worksheet->getCellByColumnAndRow(6, $row)->getFormattedValue(), 'Y-m-d', 'Y-m-d'), "DATE");
//            $Inserir->setParametros("TIPO_VENDA", $worksheet->getCellByColumnAndRow(7, $row)->getValue(), "VARCHAR");
//            $Inserir->setParametros("DATA_PROPOSTA", $Variaveis->FormatarData($worksheet->getCellByColumnAndRow(8, $row)->getFormattedValue(), 'Y-m-d', 'Y-m-d'), "DATE");
//            $Inserir->setParametros("VALOR_RECURSOS_PROPRIOS_PROPOSTA", $worksheet->getCellByColumnAndRow(9, $row)->getFormattedValue(), "MONEY");
//            $Inserir->setParametros("VALOR_FGTS_PROPOSTA", $worksheet->getCellByColumnAndRow(10, $row)->getFormattedValue(), "MONEY");
//            $Inserir->setParametros("VALOR_FINANCIADO_PROPOSTA", $worksheet->getCellByColumnAndRow(11, $row)->getFormattedValue(), "MONEY");
//            $Inserir->setParametros("VALOR_PARCELADO_PROPOSTA", $worksheet->getCellByColumnAndRow(12, $row)->getFormattedValue(), "MONEY");
//            $Inserir->setParametros("VALOR_TOTAL_PROPOSTA", $worksheet->getCellByColumnAndRow(13, $row)->getFormattedValue(), "MONEY");
//            $Inserir->setParametros("VALOR_TOTAL_CONTRATO", $worksheet->getCellByColumnAndRow(14, $row)->getFormattedValue(), "MONEY");
//            $Inserir->setParametros("VALOR_TOTAL_RECEBIDO", $worksheet->getCellByColumnAndRow(15, $row)->getFormattedValue(), "MONEY");
//            $Inserir->setParametros("DATA_ULTIMO_RECEBIMENTO", $Variaveis->FormatarData($worksheet->getCellByColumnAndRow(16, $row)->getFormattedValue(), 'Y-m-d', 'Y-m-d'), "DATE");
//            $Inserir->setParametros("PV_RECEBIMENTO", $worksheet->getCellByColumnAndRow(17, $row)->getFormattedValue(), "INT");
//            $Inserir->setParametros("NO_AGENCIA_CONTRATACAO", $worksheet->getCellByColumnAndRow(18, $row)->getValue(), "VARCHAR");
//            $Inserir->setParametros("VENCIMENTO_PP15", $Variaveis->FormatarData($worksheet->getCellByColumnAndRow(19, $row)->getFormattedValue(), 'Y-m-d', 'Y-m-d'), "DATE");
//
//            $Inserir->Execute();
//
//        }
//    }
//}
//
//$E = new \Excell\Excel();
//$Arquivo = '../../sistemas/sisadj/contratacao/planilhas/ANALITICO_VENDAS_A_CADASTRAR_GILIE_SP.xlsx';
//$E->setArquivo($Arquivo);
//$E->Importar();
//
//$Arquivo = '../../sistemas/sisadj/contratacao/planilhas/ANALITICO_VENDAS_A_COMPLEMENTAR_GILIE_SP.xlsx';
//$E->setArquivo($Arquivo);
//$E->Importar();
//
//$Arquivo = '../../sistemas/sisadj/contratacao/planilhas/ANALITICO_VENDAS_INCONSISTENTES_GILIE_SP.xlsx';
//$E->setArquivo($Arquivo);
//$E->Importar();