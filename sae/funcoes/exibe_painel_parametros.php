<?php
//Exibe Painel de Parametrização do Atendimento da OM Selecionada
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/parametro.php");
$id_unidade_sfpc = $_GET['id_unidade'];


?>
<div class="col-md-4" id="div_parametro_expediente">
    <label>HORÁRIO DO EXPEDIENTE</label>
    <table class='table table-condensed'>
          
        <tr>
            <td>
             
                  <input type="checkbox" id='chk_exp_manha' name='chk_exp_manha' onclick='selecionaExpedienteManha()' value="" checked>
                   MANHÃ
                
            </td>
            
            <td>
                <?php
                   //CARREGA COMBO INICIO EXPED MANHA
                  $valor = retornaValorParametro($id_unidade_sfpc,2,'cmb_inicio_exp_manha');
                  $array_hora_inicio_manha = array("08:00","08:15","08:30","08:45","09:00","09:15","09:30","09:45","10:00","10:15","10:30","10:45","11:00","11:15","11:30","11:45","12:00");
                  
                  echo("<select id='cmb_inicio_exp_manha' name='cmb_inicio_exp_manha' class='form-control input-sm' onchange='exibeHorarioNovaDataAgendaAtualizaParametro(\"cmb_inicio_exp_manha\")'>");
                  for($h = 0; $h < count($array_hora_inicio_manha); $h++)
                  {
                    if($array_hora_inicio_manha[$h] == $valor)
                    {
                        echo("<option selected>".$array_hora_inicio_manha[$h]."</option>");
                    }
                    else
                    {
                        echo("<option>".$array_hora_inicio_manha[$h]."</option>");
                    }

                  }
                  echo("</select>");
                ?>
              
                
                        
            
            </td>
            <td>
              <label>-</label>
            </td>
            <td>

                <?php
                  //CARREGA COMBO FIM EXPED MANHA
                  $valor = retornaValorParametro($id_unidade_sfpc,2,'cmb_fim_exp_manha');
                  $array_hora_fim_manha = array("08:00","08:15","08:30","08:45","09:00","09:15","09:30","09:45","10:00","10:15","10:30","10:45","11:00","11:15","11:30","11:45","12:00");
                  
                  echo("<select id='cmb_fim_exp_manha' name='cmb_fim_exp_manha' class='form-control input-sm' onchange='exibeHorarioNovaDataAgendaAtualizaParametro(\"cmb_fim_exp_manha\")'>");
                  for($h = 0; $h < count($array_hora_fim_manha); $h++)
                  {
                    if($array_hora_fim_manha[$h] == $valor)
                    {
                        echo("<option selected>".$array_hora_fim_manha[$h]."</option>");
                    }
                    else
                    {
                        echo("<option>".$array_hora_fim_manha[$h]."</option>");
                    }

                  }
                  echo("</select>");
                ?>
              
                              
            
            </td>
        </tr>  
            
        <tr>
            <td>
            
                  <input type="checkbox" id='chk_exp_tarde' name='chk_exp_tarde' onclick='selecionaExpedienteTarde()' value="" checked>
                  TARDE
              
            </td>


            <td>
                  <?php
                  //CARREGA COMBO INICIO EXPED TARDE
                  $valor = retornaValorParametro($id_unidade_sfpc,2,'cmb_inicio_exp_tarde');
                  $array_hora_inicio_tarde = array("13:00","13:15","13:30","13:45","14:00","14:15","14:30","14:45","15:00","15:15","15:30","15:45","16:00","16:15","16:30","16:45","17:00","17:15","17:30","17:45","18:00","18:15","18:30");
                  
                  echo("<select id='cmb_inicio_exp_tarde' name='cmb_inicio_exp_tarde' class='form-control input-sm' onchange='exibeHorarioNovaDataAgendaAtualizaParametro(\"cmb_inicio_exp_tarde\")'>");
                  for($h = 0; $h < count($array_hora_inicio_tarde); $h++)
                  {
                    if($array_hora_inicio_tarde[$h] == $valor)
                    {
                        echo("<option selected>".$array_hora_inicio_tarde[$h]."</option>");
                    }
                    else
                    {
                        echo("<option>".$array_hora_inicio_tarde[$h]."</option>");
                    }

                  }
                  echo("</select>");
                ?>
            </td>


            <td>
              <label>-</label>
            </td>
            <td>
                 <?php
                  //CARREGA COMBO FIM EXPED TARDE
                  $valor = retornaValorParametro($id_unidade_sfpc,2,'cmb_fim_exp_tarde');
                  $array_hora_fim_tarde = array("13:00","13:15","13:30","13:45","14:00","14:15","14:30","14:45","15:00","15:15","15:30","15:45","16:00","16:15","16:30","16:45","17:00","17:15","17:30","17:45","18:00","18:15","18:30");
                  
                  echo("<select id='cmb_fim_exp_tarde' name='cmb_fim_exp_tarde' class='form-control input-sm' onchange='exibeHorarioNovaDataAgendaAtualizaParametro(\"cmb_fim_exp_tarde\")'>");
                  for($h = 0; $h < count($array_hora_fim_tarde); $h++)
                  {
                    if($array_hora_fim_tarde[$h] == $valor)
                    {
                        echo("<option selected>".$array_hora_fim_tarde[$h]."</option>");
                    }
                    else
                    {
                        echo("<option>".$array_hora_fim_tarde[$h]."</option>");
                    }

                  }
                  echo("</select>");
                ?>

            </td>

        </tr>
    </table>
  </div>

  <div class="col-md-8" id="div_parametro_atendimento">
    <label>CAPACIDADE DE ATENDIMENTO</label>
    <table class='table table-condensed'>
      <tr>
            <td>
                DURAÇÃO DE CADA ATENDIMENTO
            </td>
            <td>
                  <?php
                  //CARREGA DURACAO DO ATENDIMENTO
                  $valor = retornaValorParametro($id_unidade_sfpc,2,'cmb_duracao_atendimento');
                  $array_duracao_atendimento = array("5 min","10 min", "15 min", "20 min", "30 min");
                  $array_value_duracao_atendimento = array("5","10", "15", "20", "30");
                  
                  echo("<select id='cmb_duracao_atendimento' name='cmb_duracao_atendimento' class='form-control input-sm' onchange='exibeHorarioNovaDataAgendaAtualizaParametro(\"cmb_duracao_atendimento\")'>");
                  for($h = 0; $h < count($array_duracao_atendimento); $h++)
                  {
                    if($array_value_duracao_atendimento[$h] == $valor)
                    {
                        echo("<option value=".$array_value_duracao_atendimento[$h]." selected>".$array_duracao_atendimento[$h]."</option>");
                    }
                    else
                    {
                        echo("<option value=".$array_value_duracao_atendimento[$h].">".$array_duracao_atendimento[$h]."</option>");
                    }

                  }
                  echo("</select>");
                ?>
            </td>

            <td>
                LIMITE DE AGENDAMENTOS POR DATA
            </td>
            <td>
                  <?php
                  //CARREGA DURACAO DO ATENDIMENTO
                  $valor = retornaValorParametro($id_unidade_sfpc,2,'qt_max_agendamento_data');
                  $array_limite_max_agendamento = array("1 Horário","2 Horários", "3 Horários", "4 Horários", "5 Horários");
                  $array_value_limite_max_agendamento = array("1","2", "3", "4", "5");
                  
                  echo("<select id='qt_max_agendamento_data' name='qt_max_agendamento_data' class='form-control input-sm' onchange='exibeHorarioNovaDataAgendaAtualizaParametro(\"qt_max_agendamento_data\")'>");
                  for($h = 0; $h < count($array_limite_max_agendamento); $h++)
                  {
                    if($array_value_limite_max_agendamento[$h] == $valor)
                    {
                        echo("<option value=".$array_value_limite_max_agendamento[$h]." selected>".$array_limite_max_agendamento[$h]."</option>");
                    }
                    else
                    {
                        echo("<option value=".$array_value_limite_max_agendamento[$h].">".$array_limite_max_agendamento[$h]."</option>");
                    }

                  }
                  echo("</select>");
                ?>
            </td>

      </tr>

      <tr>
            <td>
                Nº DE ATENDENTE POR HORÁRIO
            </td>
            <td>
                  <?php
                  //CARREGA QUANTIDADE DE ATENDENTES DEFINIDA POR PADRÃO DA UNIDADE
                  $valor = retornaValorParametro($id_unidade_sfpc,2,'cmb_qtd_atendente');
                  $array_qtd_atendente = array("1","2","3","4","5","6","7","8","9","10");
                 
                  
                  echo("<select id='cmb_qtd_atendente' name='cmb_qtd_atendente' class='form-control input-sm' onchange='exibeHorarioNovaDataAgendaAtualizaParametro(\"cmb_qtd_atendente\")'>");
                  for($h = 0; $h < count($array_qtd_atendente); $h++)
                  {
                    if($array_qtd_atendente[$h] == $valor)
                    {
                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
                    }
                    else
                    {
                        echo("<option>".$array_qtd_atendente[$h]."</option>");
                    }

                  }
                  echo("</select>");
                ?>

            </td>

             <td>
                LIMITE DE PROTOCOLO POR HORÁRIO
            </td>
            <td>
                  <?php
                  //CARREGA QUANTIDADE DE ATENDENTES DEFINIDA POR PADRÃO DA UNIDADE
                  $valor = retornaValorParametro($id_unidade_sfpc,2,'cmb_qtd_max_protocolo_horario');
                  $array_qtd_max_protocolo_horario = array("1","2","3","4","5","6","7","8","9","10");
                 
                  
                  echo("<select id='cmb_qtd_max_protocolo_horario' name='cmb_qtd_max_protocolo_horario' class='form-control input-sm' onchange='exibeHorarioNovaDataAgendaAtualizaParametro(\"cmb_qtd_max_protocolo_horario\")'>");
                  for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
                  {
                    if($array_qtd_max_protocolo_horario[$h] == $valor)
                    {
                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
                    }
                    else
                    {
                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
                    }

                  }
                  echo("</select>");
                ?>

            </td>
      </tr>


    </table>
  </div>





</div>