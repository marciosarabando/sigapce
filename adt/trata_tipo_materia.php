<?php
//Precisa dos dados do armamento, fornecedor e NF
				if(
					$adt_materia_tipo == 35
				 or $adt_materia_tipo == 27
				 or $adt_materia_tipo == 78
				 or $adt_materia_tipo == 84
				 or $adt_materia_tipo == 85
				 or $adt_materia_tipo == 86
				 or $adt_materia_tipo == 102
				 or $adt_materia_tipo == 28
				)   
				{

					$sql2 = "
					SELECT
					adt_arma_especie.nm_adt_arma_especie, 
					adt_arma_funcionamento.nm_adt_arma_funcionamento, 
					adt_arma_marca.nm_adt_arma_marca, 
					adt_arma_calibre.nm_adt_arma_calibre,
					adt_arma_modelo.nm_arma_modelo,
					adt_arma.nr_arma, 
					adt_arma_pais_origem.nm_adt_arma_pais_origem,
					adt_arma_acabamento.nm_adt_arma_acabamento, 
					adt_acervo.nm_adt_acervo, 
					adt_materia_arma.nr_nota_fiscal,
					adt_arma_fornecedor.nm_adt_arma_fornecedor,
					adt_arma_fornecedor.cnpj,
					adt_materia_arma.id_interessado_cedente,
					adt_arma_modelo.qtd_cano,	
					adt_arma_modelo.comprimento_cano,
					case when adt_arma_modelo.id_adt_arma_alma = 2 then 'Raiada' else 'Lisa' end as alma,
					adt_arma_modelo.nr_raias,
					case when adt_arma_modelo.sentido_raia = 1 then 'Direita' else adt_arma_modelo.sentido_raia end as alma,
					adt_arma_modelo.qtd_carregamento
					FROM adt_materia_arma 
					INNER JOIN adt_arma on adt_arma.id_adt_arma = adt_materia_arma.id_adt_arma
					INNER JOIN adt_arma_modelo ON adt_arma.id_adt_arma_modelo = adt_arma_modelo.id_adt_arma_modelo
					INNER JOIN adt_arma_especie on adt_arma_especie.id_adt_arma_especie = adt_arma_modelo.id_adt_arma_especie
					INNER JOIN adt_arma_funcionamento on adt_arma_funcionamento.id_adt_arma_funcionamento = adt_arma_modelo.id_adt_arma_funcionamento
					INNER JOIN adt_arma_marca on adt_arma_marca.id_adt_arma_marca = adt_arma_modelo.id_adt_arma_marca
					INNER JOIN adt_arma_calibre on adt_arma_calibre.id_adt_arma_calibre = adt_arma_modelo.id_adt_arma_calibre
					INNER JOIN adt_arma_pais_origem on adt_arma_pais_origem.id_adt_arma_pais_origem = adt_arma.id_adt_arma_pais_origem
					INNER JOIN adt_arma_acabamento ON adt_arma_acabamento.id_adt_arma_acabamento = adt_arma.id_adt_arma_acabamento
					INNER JOIN adt_acervo ON adt_acervo.id_adt_acervo = adt_materia_arma.id_adt_acervo  
					INNER JOIN adt_arma_fornecedor ON adt_arma_fornecedor.id_adt_arma_fornecedor = adt_materia_arma.id_adt_arma_fornecedor

					WHERE adt_materia_arma.id_adt_materia = $id_adt_materia 
					"; 

					$result2 = mysqli_query($conn,$sql2);
					$row_array2 = mysqli_fetch_row($result2); 

					$arma_especie = $row_array2[0];
					$arma_funcionamento = $row_array2[1];
					$arma_fornecedor = $row_array2[2];
					$arma_calibre = $row_array2[3];
					$arma_modelo = $row_array2[4];
					$nr_arma = $row_array2[5];
					$arma_pais_origem = $row_array2[6]; 
					$arma_acabamento = $row_array2[7]; 
					$arma_acervo = $row_array2[8]; 
					$nr_nota_fiscal = $row_array2[9];
					$nm_fornecedor = $row_array2[10];
					$cnpj_fornecedor = $row_array2[11];
					$id_interessado_cedente = $row_array2[12];
					$qtd_cano = $row_array2[13];
					$comprimento_cano = $row_array2[14];
					$alma = $row_array2[15];					
					$nr_raias = $row_array2[16];
					$sentido_raia = $row_array2[17];
					$qtd_carregamento = $row_array2[18];					
					

				} //if tipo materia = aquisicao de Armto

				//precisa dos dados do Armamento, mas não da NF e fornecedor
				if(
					$adt_materia_tipo == 25	
				 or $adt_materia_tipo == 36
				 or $adt_materia_tipo == 78
				 or $adt_materia_tipo == 88
				 or $adt_materia_tipo == 30
				 or $adt_materia_tipo == 31
				 or $adt_materia_tipo == 32
				 or $adt_materia_tipo == 33
				 or $adt_materia_tipo == 34
				 or $adt_materia_tipo == 24
				 or $adt_materia_tipo == 37
				 or $adt_materia_tipo == 38
				 or $adt_materia_tipo == 39
				 or $adt_materia_tipo == 42
				 or $adt_materia_tipo == 74
				)   
				{

					$sql2 = "
					SELECT
					adt_arma_especie.nm_adt_arma_especie, 
					adt_arma_funcionamento.nm_adt_arma_funcionamento, 
					adt_arma_marca.nm_adt_arma_marca, 
					adt_arma_calibre.nm_adt_arma_calibre,
					adt_arma_modelo.nm_arma_modelo,
					adt_arma.nr_arma, 
					adt_arma_pais_origem.nm_adt_arma_pais_origem,
					adt_arma_acabamento.nm_adt_arma_acabamento, 
					adt_acervo.nm_adt_acervo
					 
					FROM adt_materia_arma 

					INNER JOIN adt_arma on adt_arma.id_adt_arma = adt_materia_arma.id_adt_arma
					INNER JOIN adt_arma_modelo ON adt_arma.id_adt_arma_modelo = adt_arma_modelo.id_adt_arma_modelo
					INNER JOIN adt_arma_especie on adt_arma_especie.id_adt_arma_especie = adt_arma_modelo.id_adt_arma_especie
					INNER JOIN adt_arma_funcionamento on adt_arma_funcionamento.id_adt_arma_funcionamento = adt_arma_modelo.id_adt_arma_funcionamento
					INNER JOIN adt_arma_marca on adt_arma_marca.id_adt_arma_marca = adt_arma_modelo.id_adt_arma_marca
					INNER JOIN adt_arma_calibre on adt_arma_calibre.id_adt_arma_calibre = adt_arma_modelo.id_adt_arma_calibre
					INNER JOIN adt_arma_pais_origem on adt_arma_pais_origem.id_adt_arma_pais_origem = adt_arma.id_adt_arma_pais_origem
					INNER JOIN adt_arma_acabamento ON adt_arma_acabamento.id_adt_arma_acabamento = adt_arma.id_adt_arma_acabamento
					INNER JOIN adt_acervo ON adt_acervo.id_adt_acervo = adt_materia_arma.id_adt_acervo  
					
					WHERE adt_materia_arma.id_adt_materia = $id_adt_materia 
					"; 

					$result2 = mysqli_query($conn,$sql2);
					$row_array2 = mysqli_fetch_row($result2); 

					$arma_especie = $row_array2[0];
					$arma_funcionamento = $row_array2[1];
					$arma_fornecedor = $row_array2[2];
					$arma_calibre = $row_array2[3];
					$arma_modelo = $row_array2[4];
					$nr_arma = $row_array2[5];
					$arma_pais_origem = $row_array2[6]; 
					$arma_acabamento = $row_array2[7]; 
					$arma_acervo = $row_array2[8]; 
					
				} //if tipo materia = aquisicao de Armto

				//transferência de Armamento 
				if(
					$adt_materia_tipo == 46
			     or $adt_materia_tipo == 40
			     or $adt_materia_tipo == 43
			     or $adt_materia_tipo == 44
			     or $adt_materia_tipo == 47
			     or $adt_materia_tipo == 48
			     or $adt_materia_tipo == 49
			     or $adt_materia_tipo == 50
			     or $adt_materia_tipo == 51
			     or $adt_materia_tipo == 77
			    ) 
				{

					$sql2 = "
					SELECT
					adt_arma_especie.nm_adt_arma_especie, 
					adt_arma_funcionamento.nm_adt_arma_funcionamento, 
					adt_arma_marca.nm_adt_arma_marca, 
					adt_arma_calibre.nm_adt_arma_calibre,
					adt_arma_modelo.nm_arma_modelo,
					adt_arma.nr_arma, 
					adt_arma_pais_origem.nm_adt_arma_pais_origem,
					adt_arma_acabamento.nm_adt_arma_acabamento, 
					adt_acervo.nm_adt_acervo, 
					adt_materia_arma.nr_nota_fiscal,
					adt_materia_arma.id_interessado_cedente
					 
					FROM adt_materia_arma 

					INNER JOIN adt_arma on adt_arma.id_adt_arma = adt_materia_arma.id_adt_arma
					INNER JOIN adt_arma_modelo ON adt_arma.id_adt_arma_modelo = adt_arma_modelo.id_adt_arma_modelo
					INNER JOIN adt_arma_especie on adt_arma_especie.id_adt_arma_especie = adt_arma_modelo.id_adt_arma_especie
					INNER JOIN adt_arma_funcionamento on adt_arma_funcionamento.id_adt_arma_funcionamento = adt_arma_modelo.id_adt_arma_funcionamento
					INNER JOIN adt_arma_marca on adt_arma_marca.id_adt_arma_marca = adt_arma_modelo.id_adt_arma_marca
					INNER JOIN adt_arma_calibre on adt_arma_calibre.id_adt_arma_calibre = adt_arma_modelo.id_adt_arma_calibre
					INNER JOIN adt_arma_pais_origem on adt_arma_pais_origem.id_adt_arma_pais_origem = adt_arma.id_adt_arma_pais_origem
					INNER JOIN adt_arma_acabamento ON adt_arma_acabamento.id_adt_arma_acabamento = adt_arma.id_adt_arma_acabamento
					INNER JOIN adt_acervo ON adt_acervo.id_adt_acervo = adt_materia_arma.id_adt_acervo  

					WHERE adt_materia_arma.id_adt_materia = $id_adt_materia 
					"; 

					$result2 = mysqli_query($conn,$sql2);
					$row_array2 = mysqli_fetch_row($result2); 

					$arma_especie = $row_array2[0];
					$arma_funcionamento = $row_array2[1];
					$arma_fornecedor = $row_array2[2];
					$arma_calibre = $row_array2[3];
					$arma_modelo = $row_array2[4];
					$nr_arma = $row_array2[5];
					$arma_pais_origem = $row_array2[6]; 
					$arma_acabamento = $row_array2[7]; 
					$arma_acervo = $row_array2[8]; 
					$nr_nota_fiscal = $row_array2[9];
					$id_interessado_cedente = $row_array2[10];

					$sql2 = "
					SELECT nm_interessado, cpf_interessado, cnpj_interessado, cr_interessado 
					FROM interessado
					WHERE id_interessado = $id_interessado_cedente
					"; 

					$result2 = mysqli_query($conn,$sql2);
					if($result2)
					{
						$row_array2 = mysqli_fetch_row($result2); 
						$nm_interessado_cedente = $row_array2[0]; 
						$cpf_interessado_cedente = $row_array2[1];
						$cnpj_interessado_cedente = $row_array2[2];
						$cr_interessado_cedente = $row_array2[3];   
					}
				} //if tipo = 46


				//concessão, cancelamento, revalidação ou 2a via de CR PF ou PJ
				if(
						$adt_materia_tipo == 1
					 or $adt_materia_tipo == 12
					 or $adt_materia_tipo == 14
					 or $adt_materia_tipo == 22
					 or $adt_materia_tipo == 82
					 or $adt_materia_tipo == 83
					 or $adt_materia_tipo == 120
					 or $adt_materia_tipo == 121
				)

				{
					//se for PF e precisar das atividades...
					if(
						$adt_materia_tipo == 1
					 or $adt_materia_tipo == 14
					 or $adt_materia_tipo == 22
					 )
					{ 

						$sql2 = "
						SELECT adt_atividade_cr.nm_adt_atividade_cr
						FROM adt_atividade_cr, adt_materia_cr_atividade
						WHERE adt_atividade_cr.id_adt_atividade_cr = adt_materia_cr_atividade.id_adt_atividade_cr
						AND adt_materia_cr_atividade.id_adt_materia = $id_adt_materia 
						"; 

						$result2 = mysqli_query($conn,$sql2);	
						$atividades = null; 

						if($result2) {
							$regs = mysqli_num_rows($result2);

							for($linhas2=1; $linhas2 <= $regs; $linhas2 ++) {
								$row_array2 = mysqli_fetch_row($result2);

								if ($linhas2 > 1 and $linhas2 <= $regs) 
									$atividades = $atividades . ',<br>' . $row_array2[0];
								else
									$atividades = $atividades . $row_array2[0];

							} // for linhas2
							
						} //if result2

					}// if tipo = pf

					//se for PJ e precisar das atividades... 
					if(
						$adt_materia_tipo == 82
					 or $adt_materia_tipo == 83
					 or $adt_materia_tipo == 120
					 )
					{
					/*

						$sql2 = "
						SELECT adt_atividade_pj.nm_adt_atividade_pj
						FROM adt_atividade_pj, adt_materia_atv_pce_pj
						WHERE adt_atividade_pj.id_adt_atividade_pj = adt_materia_atv_pce_pj.id_adt_atividade
						AND adt_materia_atv_pce_pj.id_adt_materia = $id_adt_materia 
						"; 

						$result2 = mysqli_query($conn,$sql2);	
						$atividades = null; 

						if($result2) {
							$regs = mysqli_num_rows($result2);

							for($linhas2=1; $linhas2 <= $regs; $linhas2 ++) {
								$row_array2 = mysqli_fetch_row($result2);

								if ($linhas2 > 1 and $linhas2 <= $regs) 
									$atividades = $atividades . ', ' . $row_array2[0];
								else
									$atividades = $atividades . $row_array2[0];

							} // for linhas2
							
						} //if result2
					*/
						$atividades = 'Conforme Registro'; 
						
					} // if tipo = 104

				} //if tipo = 14 ou 22 ou 104


				//SE FOR UMA MATÉRIA QUE PRECISA DOS DADOS DO CR
				if(
					$adt_materia_tipo == 1
					or $adt_materia_tipo == 12
					or $adt_materia_tipo == 22 
					or $adt_materia_tipo == 83
					or $adt_materia_tipo == 120
					or $adt_materia_tipo == 121
				)
				{

					$sql2 = "
					SELECT nr_cr, dt_validade
					FROM adt_cr
					WHERE id_adt_materia = $id_adt_materia 
					"; 

					$result2 = mysqli_query($conn,$sql2);	
					if($result2) {
						$row_array2 = mysqli_fetch_row($result2);
						$nr_cr = $row_array2[0];
						$validade_cr = date("d/m/Y", strtotime($row_array2[1]));
					}
				} //if tipo materia = 22 (revalidação de cr)

				//SE FOR UMA mudança de vinculação de RM
				if($adt_materia_tipo == 2)
				{
					$sql2 = "
					SELECT rm_origem, rm_destino
					FROM adt_materia_vinc_rm
					WHERE id_adt_materia = $id_adt_materia 
					"; 

					$result2 = mysqli_query($conn,$sql2);	
					if($result2) {
						$row_array2 = mysqli_fetch_row($result2);
						$rm_origem = $row_array2[0];
						$rm_destino = $row_array2[1];
						$nr_cr = $cr_interessado; 
					}

				}
?>

