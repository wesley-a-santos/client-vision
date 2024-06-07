<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once $_SERVER["DOCUMENT_ROOT"].'\includes\PHPExcel\IOFactory.php';
include_once ('Classes/BancoDeDados/ConexaoExterno.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 


use Classes\Sistema\Layout;
use Classes\Entity\Usuario;


Layout::getHead();
Layout::getMenu();

$Usuario = new \Classes\LDap\Usuario();
$matricula = $Usuario->getCodigoUsuario();


?>
<style>
.formExcel{
	display: block;
	justify-content: center;
	box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
	border-radius: 5px;
	border: 1px solid #D0E0E3;
	padding: 20px;
}
</style>

    <div class="container pt-5 pb-5">
        <h3 class="mb-5 text-center">Exportar Registros Excel</h3>
		<div class="col-6 mx-auto formExcel">
		<form method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
			<div class="panel panel-default">
				<div class="panel-heading formtitulo" ></div>
				<div class="panel-body">
					<label>Utilize </label><a href="upload/Modelo.xlsx" download style="color: #F39200;"> ESTE MODELO <!--criar pasta upload e salvar arquivo Modelo.xlsx dentro--></a><label> de arquivo.</label><br>
					
					<p>Para mais orientações de preenchimento do modelo <a href="upload/Orientacoes_Preenchimento_Excel.xlsx" download> CLIQUE AQUI</a>.
					<hr>
					<br>
					<input type="file" name="file" id="file" accept=".xls,.xlsx" ><br><br>
					<div class="text-center">
						<button type="submit" class="btn btn-outline-success mt-3" id="submit" name="import" class="btn-submit">Importar</button>
					</div>
				</div>			
			</div>
			<?php

			if (isset($_POST["import"])){
				
				$allowedFileType = array('application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				
				if(in_array($_FILES["file"]["type"],$allowedFileType)){
				
					$targetPath = 'upload\\';
					
					$extensao = pathinfo($_FILES['file']['name']);
					$extensao = '.'.$extensao['extension'];// extensao do arquivo
					$nome = $_FILES['file']['name'];
					$novonome = date("Ymd_H_i_s").'_'.$matricula.$extensao;
					move_uploaded_file($_FILES['file']['tmp_name'], $targetPath.$novonome);
					
					//Use whatever path to an Excel file you need.
					$inputFileName = $targetPath.$novonome;
					
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						
						$objPHPExcel = $objReader->load($inputFileName);
						
						//var_dump($objPHPExcel->getActiveSheet()->getCell('O5')->getValue());
						//var_dump(PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getCell('O5')));
						//var_dump($objPHPExcel->getActiveSheet()->getCell('O5')->getStyle()->getNumberFormat()->getFormatCode());
						//var_dump($objPHPExcel->getActiveSheet()->getCell('O5')->getFormattedValue());
						
						//var_dump($objPHPExcel);
						//exit();
						
					} catch (Exception $e) {
						die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . 
							$e->getMessage());
					}
					
					$sheet = $objPHPExcel->getSheet(0);
					$highestRow = $sheet->getHighestRow();
					$highestColumn = $sheet->getHighestColumn();	

					echo('<table class="table table-striped table-bordered" border=1>');
					
					$count = 0;

					for ($row = 1; $row <= $highestRow; $row++) { 
							$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
							//var_dump($rowData);
							//exit();
							
							//Prints out data in each row.
							//Replace this with whatever you want to do with the data.

							if($row > 1 && isset($rowData[0][1])) {
									
									//echo '<tr><td>'.$rowData[0][1].'</td>';
								
									//$CPFCNPJ = preg_replace('#[^0-9]#', '',$rowData[0][0]);
									$DataDemanda = $rowData[0][0] == '' ? '' : gmdate("Y-m-d",($rowData[0][0] - 25569) * 86400);
									$CodigoUnidadeOrigem = $rowData[0][1];
									$CodigoUsuario = $rowData[0][2];
									$GrauSigilo = $rowData[0][3];
									$TipoCliente = $rowData[0][4];
									$Detalhamento = $rowData[0][5];
									$NumeroContrato = $rowData[0][6];
									$CpfCnpjCliente = $rowData[0][7];
									$NomeCliente = $rowData[0][8];
									$CodigoUnidadeContratacao = $rowData[0][9];
									$NumeroProdutoContrato = $rowData[0][10];
									$SistemaOrigem = $rowData[0][11];
									$TipoServico = $rowData[0][12];
									$StatusDemanda = $rowData[0][13]; 
									$DemandaIDOrigem = $rowData[0][14] == '' ? 'NULL' : $rowData[0][14];
								
									try {
									
										$query = "insert into[Externo].[dbo].[VisaoCliente_ExportaExcel]([DataDemanda],[CodigoUnidadeOrigem],[CodigoUsuario],[GrauSigilo],[TipoCliente],[Detalhamento],[NumeroContrato],[CpfCnpjCliente],[NomeCliente],[CodigoUnidadeContratacao],[NumeroProdutoContrato],[SistemaOrigem],[TipoServico],[StatusDemanda],[DemandaIDOrigem]) ";
										
										$query .= "values('$DataDemanda','$CodigoUnidadeOrigem','$CodigoUsuario','$GrauSigilo','$TipoCliente','$Detalhamento','$NumeroContrato','$CpfCnpjCliente','$NomeCliente','$CodigoUnidadeContratacao',$NumeroProdutoContrato,'$SistemaOrigem','$TipoServico','$StatusDemanda',$DemandaIDOrigem); ";
										

										$conexao = \Conexao::pegarConexaoExterno();
										$statement = $conexao->prepare($query);
										$statement->execute();	

										if (!empty($statement)) {
											$type = "success";
											$count++;
											//echo '<td>'. $count .'</td></tr>';
											$message = $count." Registros importados com sucesso!";									
										}
										
										//echo $query.'br';
										
									}catch (Exception $e) {
										$type = "error";
										$message = 'Problema ao importar dados do Excel: '.  $e->getMessage(). "\n"; //Resposta para o desenvolvedor
										//$message = 'Problema ao importar dados do Excel. Entre em contato com o desenvolvedor do sistema.'; // Resposta para o usuário
									}
							}
						}
						
						$query2 = "EXEC [dbo].[sp_importa_upload_excel_visao]";
						$conexao = \Conexao::pegarConexaoExterno();
						$statement = $conexao->prepare($query2);
						$statement->execute();
						
						echo('</table>');	
						
					}else{ 
						$type = "error";
						$message = "Tipo de arquivo inválido. Escolha um arquivo Excel.";
					}
				}
				?>	
				<div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
				<div id="processamento">Importando... <img src="../../img/icones/loading.gif"></div>
			</form>	
		</div>

	</div>
	
<?php Layout::getFoot(); ?>	

<script type="text/javascript" src="visao.js"></script>
<script type="text/javascript" src="index.js"></script>	
