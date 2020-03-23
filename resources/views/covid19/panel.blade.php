@extends('layouts/panel')

@section('pageTitle', 'COVID 19')

@section('style')
@parent
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    .amb {
        width: 100%;
        border-style: solid;
        border-width: 1px;
        border-radius: 0.5vw;
        padding-left: 1vw;
        padding-right: 1vw;
        padding-top: 1vh;
        padding-bottom: 1vh;
        box-shadow: 2px 2px 2px grey;
        margin-bottom: 1vh;
    }

    .amb:hover {
        background-color: rgba(0, 0, 0, 0.40);
        border-color: rgba(0, 0, 0, 0.40);
        cursor: pointer;
    }

    .amb .title {
        text-align: center;
        font-size: 150%;
        margin-bottom: 0px;
    }

    .amb .subtitle {
        margin-top: -0.5vh;
        text-align: center;
    }

    .amb .prediction {
        font-size: 70%;
    }

    .amb p {
        margin-bottom: 0;
    }

    .amb-0 {
        background-color: rgba(70, 70, 70);
        border-color: rgba(70, 70, 70, 0.15);
        color: white;
    }

    .amb-1,
    .amb-2 {
        background-color: rgba(100, 163, 56);
        border-color: rgba(100, 163, 56, 0.15);
    }

    .amb-3 {
        background-color: rgba(155, 193, 230);
        border-color: rgba(155, 193, 23, 0.15);
    }

    .amb-4 {
        background-color: rgba(50, 176, 241);
        border-color: rgba(50, 176, 241, 0.15);
    }

    .amb-5 {
        background-color: rgba(248, 203, 172);
        border-color: rgba(248, 203, 172, 0.15);
    }

    .amb-6 {
        background-color: rgba(191, 143, 0);
        border-color: rgba(191, 143, 0, 0.15);
    }

    .amb-7 {
        background-color: rgba(254, 217, 102);
        border-color: rgba(254, 217, 102, 0.15);
    }

    .amb-8 {
        background-color: rgba(218, 112, 214);
        border-color: rgba(218, 112, 214, 0.15);
    }

    .amb-9 {
        background-color: rgba(138, 43, 226);
        border-color: rgba(138, 43, 226, 0.15);
    }

    .case-pending {
        background-color: white;
        border-color: rgba(0, 0, 0, 0.15);
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css" />
@endsection

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10" style="margin-top:1rem;">
    <div class="container">
        <button type="button" class="btn btn-primary" data-current="panel" onclick="SwapPanel()"
            id="SwapPanel">Histórico de Casos</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nova_ocorrencia"
            id="nova-occorencia-button">Nova
            Ocorrência</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nova_ambulancia"
            id="nova-ambulancia-button">Nova
            Ambulância</button>
        <div class="row" id="panel">
            <div class="col-md-4">
                <h3>Ambulâncias Ativas</h3>
                <div id="active_ambulances">
                </div>
            </div>
            <div class="col-md-4">
                <h3>Ambulâncias</h3>
                <div id="ambulances">
                </div>
            </div>
            <div class="col-md-4">
                <h3>Ocorrências Pendentes</h3>
                <div id="open_cases">
                </div>
            </div>
        </div>
        <div class="row" id="cases_history" style="display:none">
            <div class="col-md-12" style="text-align: center;">
                <h3>Ocorrências</h3>
                <h6>Atualize a página para atualizar os casos</h6>
                <table id="all_cases" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data Ativação SALOP</th>
                            <th>Data Disponivel</th>
                            <th>Estrutura</th>
                            <th>Identificação de Viatura</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cases as $case)
                        <tr>
                            <td>{{$case->id}}</td>
                            <td>{{$case->status_SALOP_activation}}</td>
                            <td>{{$case->status_available == null ? "Sem Informação": $case->status_available}}</td>
                            <td>{{$case->structure}}</td>
                            <td>{{$case->vehicle_identification}}</td>
                            <td>{{$case->complete_source()}}</td>
                            <td>{{$case->destination}}</td>
                            <td><a href="#" onclick="openCase({{$case->id}})">Abrir</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<div class="modal" id="nova_ocorrencia" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Ocorrência</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-inline">
                    <p>Número CODU: <input type="number" id="nova_ocorrencia_numero_codu"
                            class="form-control mb-2 mr-sm-2" placeholder="Número CODU" /> Sem Número: <input
                            id="nova_ocorrencia_sem_numero" class="form-control form-check-input" type="checkbox"
                            value=""></p>
                </div>
                <div class="form-inline">
                    <p>Localização CODU: <select id="nova_ocorrencia_localizacao_codu"
                            class="form-control  mb-2 mr-sm-2" placeholder="Localização CODU">
                            <option value="1">Lisboa</option>
                            <option value="2">Porto</option>
                            <option value="3">Coimbra</option>
                            <option value="4">Sala de Crise</option>
                        </select> Sem Localização: <input id="nova_ocorrencia_sem_localizacao"
                            class="form-control form-check-input" type="checkbox" value=""></p>
                </div>
                <div class="form-inline">
                    <p>Meio de Ativação: <select id="nova_ocorrencia_activation_mean" class="form-control">
                            <option value="CNE">CNE</option>
                            <option value="INEM">INEM</option>
                            <option value="Outra">Outra</option>
                        </select>
                        Especificar: <input id="nova_ocorrencia_activation_mean_specify"
                            class="form-control form-check-input" type="text" value="">
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="newCase()">Adicionar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="nova_ambulancia" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Ambulância</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-inline">
                    <p>Estrutura: <input type="text" id="nova_ambulancia_structure" class="form-control mb-2 mr-sm-2"
                            placeholder="Estrutura" /> </p>

                </div>
                <div class="form-inline">
                    <p>Região: <select id="nova_ambulancia_region" class="form-control  mb-2 mr-sm-2"
                            placeholder="Região">
                            <option value="Norte">Norte</option>
                            <option value="Centro">Centro</option>
                            <option value="Lisboa">Lisboa</option>
                            <option value="Alentejo">Alentejo</option>
                            <option value="Sul">Sul</option>
                        </select></p>

                </div>
                <div class="form-inline">
                    <p>Identificação de Viatura (Nº de Cauda): <input type="text"
                            id="nova_ambulancia_vehicle_identification" class="form-control mb-2 mr-sm-2"
                            placeholder="Identificação de Viatura (Nº de Cauda)" /> </p>
                </div>
                <div class="form-inline">
                    <p>Prevenção Ativa: <select id="nova_ambulancia_active_prevention"
                            class="form-control  mb-2 mr-sm-2" placeholder="Prevenção Ativa">
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="newAmbulance()">Adicionar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="case" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ocorrência #<span id="case_id"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <h4>Dados de Activação</h4>
                        <div class="form-inline">
                            <div id="activation_information_CODU_number_display">
                                <p><b>Número CODU:</b> <a href="#" onclick="updateActivationInformationCODUnumber()"
                                        id="activation_information_CODU_number"></a></p>
                            </div>
                            <div id="activation_information_CODU_number_edit" style="display: none;">
                                <p><b>Número CODU:</b> <input autocomplete="off" type="number"
                                        id="activation_information_CODU_number_update" class="form-control mb-2 mr-sm-2"
                                        placeholder="Número CODU" /> Sem Número: <input
                                        id="activation_information_CODU_number_update_sem_numero"
                                        class="form-control form-check-input" type="checkbox" value=""> <a href="#"
                                        onclick="cancelUpdateActivationInformationCODUnumber()">Cancelar</a> <a href="#"
                                        onclick="submitUpdateActivationInformationCODUnumber()">Atualizar</a></p>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div id="activation_information_CODU_localization_display">
                                <p><b>Localização CODU:</b> <a href="#"
                                        onclick="updateActivationInformationCODUlocalization()"
                                        id="activation_information_CODU_localization"></a></p>
                            </div>
                            <div id="activation_information_CODU_localization_edit" style="display: none;">
                                <p><b>Localização CODU:</b> <select autocomplete="off"
                                        id="activation_information_CODU_localization_update"
                                        class="form-control  mb-2 mr-sm-2" placeholder="Localização CODU">
                                        <option value="1">Lisboa</option>
                                        <option value="2">Porto</option>
                                        <option value="3">Coimbra</option>
                                        <option value="4">Sala de Crise</option>
                                    </select> Sem Localização:
                                    <input autocomplete="off"
                                        id="activation_information_CODU_localization_update_sem_localizacao"
                                        class="form-control form-check-input" type="checkbox" value=""> <a href="#"
                                        onclick="cancelUpdateActivationInformationCODUlocalization()">Cancelar</a> <a
                                        href="#"
                                        onclick="submitUpdateActivationInformationCODUlocalization()">Atualizar</a></p>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div id="activation_information_activation_mean_display">
                                <p><b>Meio de Ativação:</b> <a href="#"
                                        onclick="updateActivationInformationActivationMean()"
                                        id="activation_information_activation_mean"></a></p>
                            </div>
                            <div id="activation_information_activation_mean_edit" style="display: none;">
                                <p><b>Meio de Ativação:</b> <select autocomplete="off"
                                        id="activation_information_activation_mean_update" class="form-control">
                                        <option value="CNE">CNE</option>
                                        <option value="INEM">INEM</option>
                                        <option value="Outra">Outra</option>
                                    </select>
                                    Especificar: <input autocomplete="off"
                                        id="activation_information_activation_mean_update_specify"
                                        class="form-control form-check-input" type="text" value=""> <a href="#"
                                        onclick="cancelUpdateActivationInformationActivationMean()">Cancelar</a> <a
                                        href="#"
                                        onclick="submitUpdateActivationInformationActivationMean()">Atualizar</a>
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger" onclick="cancelCase()">Anular Ocorrência</button>
                    </div>
                    <div class="col-sm-4">
                        <div id="case_ambulance">
                            <h4>Dados da Ambulância</h4>
                            <div id="occorrence_ambulance">
                                <p><b>Estrutura:</b> <span id="ambulance_structure">vehicle structure</span></p>
                                <p><b>Tipo de Ambulância:</b> <span id="ambulance_vehicle_type">vehicle type</span></p>
                                <p><b>Nº de Cauda:</b> <span id="ambulance_vehicle_identification">vehicle
                                        identification</span></p>
                                <button type="button" class="btn btn-primary" onclick="reactivateAmbulance()">Modificar
                                    Ambulância</button>
                            </div>
                            <div id="occorrence_ambulance_create" style="display:none">
                                <p>Ambulância: <select class="form-control" id="occorrence_ambulance_create_amb">
                                        <option value=""></option>
                                        <option value="SIEM-PEM">SIEM-PEM</option>
                                        <option value="SIEM-RES">SIEM-RES</option>
                                    </select></p>
                                <div id="occorrence_ambulance_create_amb_non_covid19" style="display: none;">
                                    <p><b>Estrutura:</b> <input autocomplete="off" type="text"
                                            id="occorrence_ambulance_create_amb_non_covid19_structure"
                                            class="form-control" placeholder="Estrutura"></p>
                                    <p><b>Nº de Cauda:</b> <input autocomplete="off" type="text"
                                            id="occorrence_ambulance_create_amb_non_covid19_vehicle_identification"
                                            class="form-control" placeholder="Nº de Cauda"></p>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="activateAmbulance()">Accionar
                                    Ambulância</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <h4>STATUS</h4>
                        <div id="status_SALOP_activation_display">
                            <p><b>Ativação SALOP:</b> <a href="#" onclick="updateSALOPActivationStatus()"
                                    id="status_SALOP_activation">status_SALOP_activation</a></p>
                        </div>
                        <div id="status_SALOP_activation_edit" class="form-inline" style="display:none;">
                            <p><b>Ativação SALOP: </b> <input autocomplete="off" type="text"
                                    id="status_SALOP_activation_edit_input" class="form-control"
                                    placeholder="Ativação SALOP"> <a href="#"
                                    onclick="cancelUpdateSALOPActivationStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateSALOPActivationStatus()">Atualizar</a></p>
                        </div>
                        <div id="status_AMB_activation_display">
                            <p><b>Ativação AMB:</b> <a href="#" onclick="updateAMBActivationStatus()"
                                    id="status_AMB_activation">status_AMB_activation</a></p>
                        </div>
                        <div id="status_AMB_activation_edit" class="form-inline" style="display:none;">
                            <p><b>Ativação AMB: </b> <input autocomplete="off" type="text"
                                    id="status_AMB_activation_edit_input" class="form-control"
                                    placeholder="Ativação AMB"> <a href="#"
                                    onclick="cancelUpdateAMBActivationStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateAMBActivationStatus()">Atualizar</a></p>
                        </div>
                        <div id="status_base_exit_display">
                            <p><b>Saída Unidade:</b> <a href="#" onclick="updateBaseExitStatus()"
                                    id="status_base_exit">status_base_exit</a></p>
                        </div>
                        <div id="status_base_exit_edit" class="form-inline" style="display:none;">
                            <p><b>Saída Unidade: </b> <input autocomplete="off" type="text"
                                    id="status_base_exit_edit_input" class="form-control" placeholder="Saída Unidade">
                                <a href="#" onclick="cancelUpdateBaseExitStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateBaseExitStatus()">Atualizar</a></p>
                        </div>
                        <div id="status_arrival_on_scene_display">
                            <p><b>Chegada Local:</b> <a href="#" onclick="updateArrivalOnSceneStatus()"
                                    id="status_arrival_on_scene">status_arrival_on_scene</a></p>
                        </div>
                        <div id="status_arrival_on_scene_edit" class="form-inline" style="display:none;">
                            <p><b>Chegada Local: </b> <input autocomplete="off" type="text"
                                    id="status_arrival_on_scene_edit_input" class="form-control"
                                    placeholder="Chegada Local"> <a href="#"
                                    onclick="cancelUpdateArrivalOnSceneStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateArrivalOnSceneStatus()">Atualizar</a></p>
                        </div>
                        <div id="status_departure_from_scene_display">
                            <p><b>Saida Local:</b> <a href="#" onclick="updateDepartureFromSceneStatus()"
                                    id="status_departure_from_scene">status_departure_from_scene</a></p>
                        </div>
                        <div id="status_departure_from_scene_edit" class="form-inline" style="display:none;">
                            <p><b>Saida Local: </b> <input autocomplete="off" type="text"
                                    id="status_departure_from_scene_edit_input" class="form-control"
                                    placeholder="Saida Local"> <a href="#"
                                    onclick="cancelUpdateDepartureFromSceneStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateDepartureFromSceneStatus()">Atualizar</a></p>
                        </div>
                        <div id="status_arrival_on_destination_display">
                            <p><b>Chegada Destino:</b> <a href="#" onclick="updateArrivalOnDestinationStatus()"
                                    id="status_arrival_on_destination">status_arrival_on_destination</a></p>
                        </div>
                        <div id="status_arrival_on_destination_edit" class="form-inline" style="display:none;">
                            <p><b>Chegada Destino: </b> <input autocomplete="off" type="text"
                                    id="status_arrival_on_destination_edit_input" class="form-control"
                                    placeholder="Chegada Destino"> <a href="#"
                                    onclick="cancelUpdateArrivalOnDestinationStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateArrivalOnDestinationStatus()">Atualizar</a></p>
                        </div>
                        <div id="status_departure_from_destination_display">
                            <p><b>Saida Destino:</b> <a href="#" onclick="updateDepartureFromDestinationStatus()"
                                    id="status_departure_from_destination">status_departure_from_destination</a></p>
                        </div>
                        <div id="status_departure_from_destination_edit" class="form-inline" style="display:none;">
                            <p><b>Saida Destino: </b> <input autocomplete="off" type="text"
                                    id="status_departure_from_destination_edit_input" class="form-control"
                                    placeholder="Saida Destino"> <a href="#"
                                    onclick="cancelUpdateDepartureFromDestinationStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateDepartureFromDestinationStatus()">Atualizar</a></p>
                        </div>
                        <div id="status_base_return_display">
                            <p><b>Chegada Base de Desinfecção:</b> <a href="#" onclick="updateBaseReturnStatus()"
                                    id="status_base_return">status_base_return</a></p>
                        </div>
                        <div id="status_base_return_edit" class="form-inline" style="display:none;">
                            <p><b>Chegada Base de Desinfecção: </b> <input autocomplete="off" type="text"
                                    id="status_base_return_edit_input" class="form-control"
                                    placeholder="Chegada Base de Desinfecção"> <a href="#"
                                    onclick="cancelUpdateBaseReturnStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateBaseReturnStatus()">Atualizar</a></p>
                        </div>
                        <div id="status_available_display">
                            <p><b>Disponivel:</b> <a href="#" onclick="updateAvailableStatus()"
                                    id="status_available">status_available</a></p>
                        </div>
                        <div id="status_available_edit" class="form-inline" style="display:none;">
                            <p><b>Disponivel: </b> <input autocomplete="off" type="text"
                                    id="status_available_edit_input" class="form-control" placeholder="Disponivel"> <a
                                    href="#" onclick="cancelUpdateAvailableStatus()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateAvailableStatus()">Atualizar</a></p>
                        </div>
                    </div>
                </div>
                <hr />
                <div id="case_data">
                    <h4>Dados Ocorrência</h4>
                    <div id="occorrence_data">
                        <div class="row">
                            <div class="col-sm-8">
                                <div id="event_street_display">
                                    <p><b>Rua:</b> <a href="#" onclick="updateEventStreet()"
                                            id="event_street">street</a></p>
                                </div>
                                <div id="event_street_edit" class="form-inline" style="display:none;">
                                    <p><b>Rua: </b> <input autocomplete="off" type="text" id="event_street_edit_input"
                                            class="form-control" placeholder="Rua"> <a href="#"
                                            onclick="cancelUpdateEventStreet()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventStreet()">Atualizar</a></p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="event_ref_display">
                                    <p><b>Pontos de Referência:</b> <a href="#" onclick="updateEventRef()"
                                            id="event_ref">ref</a></p>
                                </div>
                                <div id="event_ref_edit" class="form-inline" style="display:none;">
                                    <p><b>Rua: </b> <input autocomplete="off" type="text" id="event_ref_edit_input"
                                            class="form-control" placeholder="Pontos de Referência"> <a href="#"
                                            onclick="cancelUpdateEventRef()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventRef()">Atualizar</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="event_parish_display">
                                    <p><b>Freguesia:</b> <a href="#" onclick="updateEventParish()"
                                            id="event_parish">parish</a></p>
                                </div>
                                <div id="event_parish_edit" class="form-inline" style="display:none;">
                                    <p><b>Freguesia: </b> <input autocomplete="off" type="text"
                                            id="event_parish_edit_input" class="form-control" placeholder="Freguesia">
                                        <a href="#" onclick="cancelUpdateEventParish()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventParish()">Atualizar</a></p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="event_county_display">
                                    <p><b>Concelho:</b> <a href="#" onclick="updateEventCounty()"
                                            id="event_county">county</a></p>
                                </div>
                                <div id="event_county_edit" class="form-inline" style="display:none;">
                                    <p><b>Concelho: </b> <input autocomplete="off" type="text"
                                            id="event_county_edit_input" class="form-control" placeholder="Concelho"> <a
                                            href="#" onclick="cancelUpdateEventCounty()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventCounty()">Atualizar</a></p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="event_district_display">
                                    <p><b>Distrito:</b> <a href="#" onclick="updateEventDistrict()"
                                            id="event_district">distrito</a></p>
                                </div>
                                <div id="event_district_edit" class="form-inline" style="display:none;">
                                    <p><b>Distrito: </b> <input autocomplete="off" type="text"
                                            id="event_district_edit_input" class="form-control" placeholder="Distrito">
                                        <a href="#" onclick="cancelUpdateEventDistrict()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventDistrict()">Atualizar</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div id="event_source_display">
                                    <p><b>Origem:</b> <a href="#" onclick="updateEventSource()"
                                            id="event_source">Origem</a></p>
                                </div>
                                <div id="event_source_edit" class="form-inline" style="display:none;">
                                    <p><b>Origem: </b> <select autocomplete="off" class="form-control"
                                            id="event_source_edit_input">
                                            <option value="Domicílio">Domicílio</option>
                                            <option value="Via pública">Via pública</option>
                                            <option value="Espaço privado">Espaço privado</option>
                                            <option value="Unidade de Saúde">Unidade de Saúde</option>
                                        </select> Especificar: <input autocomplete="off" type="text"
                                            id="event_source_edit_input_specify" class="form-control"
                                            placeholder="Especificar"><a href="#"
                                            onclick="cancelUpdateEventSource()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventSource()">Atualizar</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div id="event_destination_display">
                                    <p><b>Destino:</b> <a href="#" onclick="updateEventDestination()"
                                            id="event_destination">destino</a></p>
                                </div>
                                <div id="event_destination_edit" class="form-inline" style="display:none;">
                                    <p><b>Destino: </b> <input autocomplete="off" type="text"
                                            id="event_destination_edit_input" class="form-control"
                                            placeholder="Destino"> <a href="#"
                                            onclick="cancelUpdateEventDestination()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventDestination()">Atualizar</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div id="event_doctor_responsible_on_scene_display">
                                    <p><b>Médico Resposável (Local):</b> <a href="#"
                                            onclick="updateEventDoctorResponsibleOnScene()"
                                            id="event_doctor_responsible_on_scene">médico local</a></p>
                                </div>
                                <div id="event_doctor_responsible_on_scene_edit" class="form-inline"
                                    style="display:none;">
                                    <p><b>Médico Resposável (Local): </b> <input autocomplete="off" type="text"
                                            id="event_doctor_responsible_on_scene_edit_input" class="form-control"
                                            placeholder="Sem Informação"> <a href="#"
                                            onclick="cancelUpdateEventDoctorResponsibleOnScene()">Cancelar</a> <a
                                            href="#" onclick="submitUpdateEventDoctorResponsibleOnScene()">Atualizar</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div id="event_doctor_responsible_on_destination_display">
                                    <p><b>Médico Resposável (Destino):</b> <a href="#"
                                            onclick="updateEventDoctorResponsibleOnDestination()"
                                            id="event_doctor_responsible_on_destination">médico destino</a></p>
                                </div>
                                <div id="event_doctor_responsible_on_destination_edit" class="form-inline"
                                    style="display:none;">
                                    <p><b>Médico Resposável (Destino): </b> <input autocomplete="off" type="text"
                                            id="event_doctor_responsible_on_destination_edit_input" class="form-control"
                                            placeholder="Sem Informação"> <a href="#"
                                            onclick="cancelUpdateEventDoctorResponsibleOnDestination()">Cancelar</a> <a
                                            href="#"
                                            onclick="submitUpdateEventDoctorResponsibleOnDestination()">Atualizar</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <div id="event_on_scene_units_display">
                                    <p><b>Outros meios no local:</b> <a href="#" onclick="updateEventOnSceneUnits()"
                                            id="event_on_scene_units">médico destino</a></p>
                                </div>
                                <div id="event_on_scene_units_edit" class="form-inline" style="display:none;">
                                    <p><b>Outros meios no local: </b> <select autocomplete="off" class="form-control"
                                            id="event_on_scene_units_edit_input">
                                            <option value="">Sem Informação</option>
                                            <option value="1">Sim</option>
                                            <option value="0">Não</option>
                                        </select> Quais?: <input autocomplete="off" type="text"
                                            id="event_on_scene_units_edit_input_specify" class="form-control"
                                            placeholder="Especificar"> <a href="#"
                                            onclick="cancelUpdateEventOnSceneUnits()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventOnSceneUnits()">Atualizar</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div id="event_total_distance_display">
                                    <p><b>Distância Total:</b> <a href="#" onclick="updateEventTotalDistance()"
                                            id="event_total_distance">Distância Total</a></p>
                                </div>
                                <div id="event_total_distance_edit" class="form-inline" style="display:none;">
                                    <p><b>Distância Total: </b> <input autocomplete="off" type="text"
                                            id="event_total_distance_edit_input" class="form-control"
                                            placeholder="Distância Total">
                                        <a href="#" onclick="cancelUpdateEventTotalDistance()">Cancelar</a> <a href="#"
                                            onclick="submitUpdateEventTotalDistance()">Atualizar</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="occorrence_data_create" style="display:none">
                        <div id="occorence_data_create_validate">
                            <button type="button" class="btn btn-primary" onclick="openDataInsert()">Anexar Dados
                                Ocorrência</button>
                        </div>
                        <div id="occorence_data_create_insert" style="display: none">
                            <div class="row">
                                <div class="col-sm-8 form-inline">
                                    <p><b>Rua:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_street" class="form-control" placeholder="Rua">
                                    </p>
                                </div>
                                <div class="col-sm-4 form-inline">
                                    <p><b>Pontos de Referência:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_ref" class="form-control"
                                            placeholder="Pontos de Referência"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 form-inline">
                                    <p><b>Freguesia:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_parish" class="form-control"
                                            placeholder="Freguesia"></p>
                                </div>
                                <div class="col-sm-4 form-inline">
                                    <p><b>Concelho:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_county" class="form-control"
                                            placeholder="Concelho"></p>
                                </div>
                                <div class="col-sm-4 form-inline">
                                    <p><b>Distrito:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_district" class="form-control"
                                            placeholder="Distrito"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 form-inline">
                                    <p><b>Origem:</b> <select autocomplete="off" class="form-control"
                                            id="occorence_data_create_origem">
                                            <option value="Domicílio">Domicílio</option>
                                            <option value="Via pública">Via pública</option>
                                            <option value="Espaço privado">Espaço privado</option>
                                            <option value="Unidade de Saúde">Unidade de Saúde</option>
                                        </select> Especificar: <input autocomplete="off" type="text"
                                            id="occorence_data_create_origem_specify" class="form-control"
                                            placeholder="Especificar"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-inline">
                                    <p><b>Destino:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_destino" class="form-control"
                                            placeholder="Destino"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-inline">
                                    <p><b>Médico Responsável no Local:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_medico_local" class="form-control"
                                            placeholder="Sem Informação"></p>
                                </div>
                                <div class="col-sm-6 form-inline">
                                    <p><b>Médico Responsável no Destino:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_medico_destino" class="form-control"
                                            placeholder="Sem Informação"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-9 form-inline">
                                    <p><b>Outros Meios no Local:</b> <select autocomplete="off" class="form-control"
                                            id="occorence_data_create_outros_meios">
                                            <option value="">Sem Informação</option>
                                            <option value="1">Sim</option>
                                            <option value="0">Não</option>
                                        </select> Quais?: <input autocomplete="off" type="text"
                                            id="occorence_data_create_outros_meios_specify" class="form-control"
                                            placeholder="Especificar"></p>
                                </div>
                                <div class="col-sm-3 form-inline">
                                    <p><b>Distância Total:</b> <input autocomplete="off" type="text"
                                            id="occorence_data_create_distancia_total" class="form-control"
                                            placeholder="Distância Total"></p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning" onclick="cancelDataInsert()">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="submitDataInsert()">Guardar</button>
                        </div>
                    </div>
                    <hr />
                </div>
                <div id="case_team">
                    <h4>Dados Equipa</h4>
                    <div id="occorrence_team">
                    </div>
                    <h6>Adicionar Membro de Equipa</h6>
                    <div class="form-row align-items-center">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="case_team_name" placeholder="Nome">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="case_team_age" placeholder="Idade">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="case_team_contact" placeholder="Contacto">
                        </div>
                        <div class="col-sm-auto">
                            <select class="form-control" id="case_team_type" placeholder="Tipo">
                                <option value="Condutor">Condutor</option>
                                <option value="Socorrista">Socorrista</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary"
                                onclick="insertTeamMember()">Adicionar</button>
                        </div>
                    </div>
                    <hr />
                </div>
                <div id="case_patient">
                    <h4>Dados Vitima</h4>
                    <div id="occorrence_patient">
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Adicionar Vitima</h6>
                            <p>Deixar em branco para registar sem informação</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-inline">
                            <p><b>Nº RNU:</b> <input autocomplete="off" type="number" id="case_patient_RNU"
                                    class="form-control" placeholder="Nº RNU">
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-inline">
                            <p><b>Nome Próprio (2 primeiras consoantes):</b> <input autocomplete="off" type="text"
                                    id="case_patient_firstname" class="form-control"
                                    placeholder="2 primeiras consoantes"></p>
                        </div>
                        <div class="col-sm-6 form-inline">
                            <p><b>Último Apelido (3 primeiras consoantes):</b> <input autocomplete="off" type="text"
                                    id="case_patient_lastname" class="form-control"
                                    placeholder="3 primeiras consoantes"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-inline">
                            <p><b>Género:</b> <select autocomplete="off" class="form-control" id="case_patient_genero">
                                    <option value="">Sem Informação</option>
                                    <option value="0">Masculino</option>
                                    <option value="1">Feminino</option>
                                </select></p>
                        </div>
                        <div class="col-sm-6 form-inline">
                            <p><b>Data de Nascimento:</b> <input autocomplete="off" type="date" id="case_patient_DoB"
                                    class="form-control"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 form-inline">
                            <p><b>Caso Suspeito COVID-19:</b> <select autocomplete="off" class="form-control"
                                    id="case_patient_suspect">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select></p>
                        </div>
                        <div class="col-sm-3 form-inline">
                            <p><b>Validação:</b> <select autocomplete="off" class="form-control"
                                    id="case_patient_suspect_validation">
                                    <option value="">Sem Informação</option>
                                    <option value="CODU">CODU</option>
                                    <option value="Equipa">Equipa</option>
                                    <option value="Hospital">Hospital</option>
                                </select></p>
                        </div>
                        <div class="col-sm-6 form-inline">
                            <p><b>Caso Confirmado COVID-19:</b> <select autocomplete="off" class="form-control"
                                    id="case_patient_confirmed">
                                    <option value="">Sem Informação</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-inline">
                            <p><b>Realizados cuidados invasivos:</b><select autocomplete="off" class="form-control"
                                    id="case_patient_invasive_care">
                                    <option value="">Sem Informação</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary" onclick="insertPatient()">Adicionar
                                Vitima</button>
                        </div>
                    </div>
                </div>
                <hr />
                <div id="case_operators">
                    <h4>Operadores</h4>
                    <div id="case_operators_inside">
                    </div>
                    <hr />
                </div>
                <div id="case_notes">
                    <h4>Notas</h4>
                    <div class="form-group">
                        <label>As notas são apagadas no encerramento da ocorrência.</label>
                        <textarea class="form-control" id="case_notes_textarea" rows="3"></textarea>
                        <button type="button" class="btn btn-primary" onclick="updateCaseNotes()">Guardar</button>
                    </div>
                </div>
                <hr />
                <div id="case_observations">
                    <h4>Observações</h4>
                    <div id="case_observations_inside">

                    </div>
                    <div class="form-group">
                        <hr />
                        <textarea class="form-control" id="case_observations_textarea" rows="3"
                            placeholder="Observação"></textarea>
                        <button type="button" class="btn btn-primary" onclick="addObservation()">Adicionar
                            Observação</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="ambulance" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ambulancia #<span id="ambulance_id"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9">
                        <h4>Informações</h4>
                        <div id="open_ambulance_structure_display">
                            <p><b>Estrutura:</b> <a href="#" onclick="updateOpenAmbulanceStructure()"
                                    id="open_ambulance_structure"></a></p>
                        </div>
                        <div id="open_ambulance_structure_edit" class="form-inline" style="display:none;">
                            <p><b>Estrutura: </b> <input autocomplete="off" type="text"
                                    id="open_ambulance_structure_edit_input" class="form-control"
                                    placeholder="Estrutura">
                                <a href="#" onclick="cancelUpdateOpenAmbulanceStructure()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateOpenAmbulanceStructure()">Atualizar</a></p>
                        </div>
                        <div id="open_ambulance_region_display">
                            <p><b>Região:</b> <a href="#" onclick="updateOpenAmbulanceRegion()"
                                    id="open_ambulance_region"></a></p>
                        </div>
                        <div id="open_ambulance_region_edit" class="form-inline" style="display:none;">
                            <p><b>Região: </b> <input autocomplete="off" type="text"
                                    id="open_ambulance_region_edit_input" class="form-control" placeholder="Região">
                                <a href="#" onclick="cancelUpdateOpenAmbulanceRegion()">Cancelar</a> <a href="#"
                                    onclick="submitUpdateOpenAmbulanceRegion()">Atualizar</a></p>
                        </div>
                        <div id="open_ambulance_region_display">
                            <p><b>Identificação de Viatura (Nº de Cauda):</b> <a href="#"
                                    onclick="updateOpenAmbulanceVehicleIdentification()"
                                    id="open_ambulance_vehicle_identification"></a></p>
                        </div>
                        <div id="open_ambulance_vehicle_identification_edit" class="form-inline" style="display:none;">
                            <p><b>Identificação de Viatura (Nº de Cauda): </b> <input autocomplete="off" type="text"
                                    id="open_ambulance_vehicle_identification_edit_input" class="form-control"
                                    placeholder="Identificação de Viatura (Nº de Cauda)">
                                <a href="#" onclick="cancelUpdateOpenAmbulanceVehicleIdentification()">Cancelar</a> <a
                                    href="#" onclick="submitUpdateOpenAmbulanceVehicleIdentification()">Atualizar</a>
                            </p>
                        </div>

                        <div id="open_ambulance_active_prevention_display">
                            <p><b>Tipo de Prevenção:</b> <a href="#" onclick="updateOpenAmbulanceActivePrevention()"
                                    id="open_ambulance_active_prevention"></a></p>
                        </div>
                        <div id="open_ambulance_active_prevention_edit" class="form-inline" style="display:none;">
                            <p><b>Tipo de Prevenção: </b> <select id="open_ambulance_active_prevention_edit_input"
                                    class="form-control  mb-2 mr-sm-2" placeholder="Tipo de Prevenção">
                                    <option value="1">Prevenção Ativa</option>
                                    <option value="0">Prevenção Passiva</option>
                                </select>
                                <a href="#" onclick="cancelUpdateOpenAmbulanceActivePrevention()">Cancelar</a> <a
                                    href="#" onclick="submitUpdateOpenAmbulanceActivePrevention()">Atualizar</a></p>
                        </div>
                        <p><b>Estado:</b> <span id="open_ambulance_status"></span></p>
                        <p><b>Última Mudança de Estado:</b> <span id="open_ambulance_status_date"></span></p>
                    </div>
                    <div class="col-md-3">
                        <h4>Previsões</h4>
                        <p>Por Implementar</p>
                    </div>
                </div>
                <div id="ambulance_contacts">
                    <h4>Contactos</h4>
                    <div id="ambulance_contacts_inside">
                    </div>
                    <hr />
                    <h6>Adicionar Contacto</h6>
                    <div class="form-row align-items-center">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="ambulance_contacts_contact"
                                placeholder="Número">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="ambulance_contacts_name" placeholder="Nome">
                        </div>
                        <div class="col-auto">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ambulance_contacts_sms">
                                <label class="form-check-label" for="ambulance_contacts_sms">
                                    Enviar SMS de Ativação
                                </label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary" onclick="addContact()">Adicionar</button>
                        </div>
                    </div>
                    <hr />
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger" id="ambulance-inop-button"
                            onclick="ambulanceINOP()">INOP</button>
                        <button type="button" class="btn btn-primary" id="ambulance-available-button"
                            onclick="ambulanceAvailable()">Disponivel</button>
                        <button type="button" class="btn btn-info" id="ambulance-on-base-button"
                            onclick="ambulanceOnBase()">Na Base</button>
                        <button type="button" class="btn btn-primary" id="ambulance-base-exit-button"
                            onclick="ambulanceBaseExit()">Saida Base</button>
                        <button type="button" class="btn btn-primary" id="ambulance-arrival-on-scene-button"
                            onclick="ambulanceArrivalOnScene()">Chegada Local</button>
                        <button type="button" class="btn btn-primary" id="ambulance-departure-from-scene-button"
                            onclick="ambulanceDepartureFromScene()">Saida Local</button>
                        <button type="button" class="btn btn-primary" id="ambulance-arrival-on-destination-button"
                            onclick="ambulanceArrivalOnDestination()">Chegada Destino</button>
                        <button type="button" class="btn btn-primary" id="ambulance-departure-from-destination-button"
                            onclick="ambulanceDepartureFromDestination()">Saida Destino</button>
                        <button type="button" class="btn btn-primary" id="ambulance-base-return-button"
                            onclick="ambulanceBaseReturn()">Chegada Base Desinfecção</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><button type="button" class="btn btn-success" id="ambulance-open-case-button"
                                style="margin-top:1vh;">Abrir Caso</button></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
</script>
<script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@parent
<script type="covid19/template" id="openCase_template">
    <a onclick="openCase({id})" data-case-id="{id}" id="openCase{id}" class="pending"><div class="amb case-pending">
        <p><b>Número Interno:</b> <span class="case_id">{id}</span>/2020</p>
        <p><b>Número CODU:</b> <span class="CODU_number">{CODU_number}</span></p>
        <p><b>Meio de Ativação:</b> <span class="activation_mean">{activation_mean}</span></p>
    </div></a>
</script>
<script type="covid19/template" id="ambulance_template">
    <a onclick="openAmbulance({id})" data-ambulance-id="{id}" data-ambulance-status="{status}" id="ambulance{id}" class="ambulance"><div class="amb amb-{status}">
        <p class="title"><b class="amb-structure">{structure}</b></p>
        <p class="subtitle"><b class="amb-region">{region}</b></p>
        <p class="subtitle"><b class="amb-identification">{vehicle_identification}</b></p>
        <p><b>Estado:</b> <span class="amb-status">{status_text}</span></p>
    </div></a>
</script>

<script type="covid19/template" id="activeAmbulance_template">
    <a onclick="openAmbulance({id})" data-ambulance-id="{id}" data-ambulance-status="{status}" id="ambulance{id}" class="ambulance"><div class="amb amb-{status}">
        <p class="title"><b>{structure}</b></p>
        <p class="subtitle"><b class="amb-region">{region}</b></p>
        <p class="subtitle"><b class="amb-identification">{vehicle_identification}</b></p>
        <p><b>Estado:</b> <span class="amb-status">{status_text}</span></p>
        <p class="prediction"><b>Previsões ainda não implementadas</b></p>
        <hr />
        <p><b>Número CODU:</b> <span class="amb-codu-number">{codu_number}</span></p>
        <hr />
        <p><b>Origem:</b> <span class="amb-source">{source}</span></p>
        <p><b>Destino:</b> <span class="amb-destination">{destination}</span></p>
    </div></a>
</script>
<script type="covid19/template" id="observation_template">
    <div class="card">
        <div class="card-header" id="observation{id}">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse"
                    data-target="#collapse_observation{id}" aria-expanded="true"
                    aria-controls="collapse_observation{id}">
                    Observação de {author} - {date}
                </button>
            </h5>
        </div>
    
        <div id="collapse_observation{id}" class="collapse show" aria-labelledby="observation{id}"
            data-parent="#case_observations_inside">
            <div class="card-body">
                {observation}
                <hr />
                <button type="button" class="btn btn-danger" onclick="removeObservation({id})">Remover Observação</button>
            </div>
        </div>
    </div>
</script>

<script type="covid19/template" id="patient_template">
    <div class="row">
        <div class="col-sm-12">
            <div id="case_patient_RNU_display{id}">
                <p><b>Nº RNU:</b> <a href="#" onclick="updatePatientRNU({id})">{RNU}</a></p>
            </div>
            <div id="case_patient_RNU_edit{id}" class="form-inline" style="display:none;">
                <p><b>Nº RNU:</b> <input autocomplete="off" type="number"
                        id="case_patient_RNU_edit_input{id}" class="form-control"
                        placeholder="Nº RNU" value="{RNU}"> <a href="#"
                        onclick="cancelUpdatePatientRNU({id})">Cancelar</a> <a href="#"
                        onclick="submitUpdatePatientRNU({id})">Atualizar</a></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div id="case_patient_firstname_display{id}">
                <p><b>Nome Próprio (2 primeiras consoantes):</b> <a href="#" onclick="updatePatientFirstname({id})">{firstname}</a></p>
            </div>
            <div id="case_patient_firstname_edit{id}" class="form-inline" style="display:none;">
                <p><b>Nome Próprio (2 primeiras consoantes):</b> <input autocomplete="off"
                        type="text" id="case_patient_firstname_edit_input{id}"
                        class="form-control" placeholder="2 primeiras consoantes" value="{firstname}"><a href="#"
                        onclick="cancelUpdatePatientFirstname({id})">Cancelar</a> <a href="#"
                        onclick="submitUpdatePatientFirstname({id})">Atualizar</a></p>
            </div>
        </div>
        <div class="col-sm-6">
            <div id="case_patient_lastname_display{id}">
                <p><b>Último Apelido (3 primeiras consoantes):</b> <a href="#" onclick="updatePatientLastname({id})">{lastname}</a></p>
            </div>
            <div id="case_patient_lastname_edit{id}" class="form-inline" style="display:none;">
                <p><b>Último Apelido (3 primeiras consoantes):</b> <input autocomplete="off"
                        type="text" id="case_patient_lastname_edit_input{id}"
                        class="form-control" placeholder="3 primeiras consoantes" value="{lastname}"> <a href="#"
                        onclick="cancelUpdatePatientLastname({id})">Cancelar</a> <a href="#"
                        onclick="submitUpdatePatientLastname({id})">Atualizar</a></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div id="case_patient_sex_display{id}">
                <p><b>Género:</b> <a href="#" onclick="updatePatientSex({id})">{sex}</a></p>
            </div>
            <div id="case_patient_sex_edit{id}" class="form-inline" style="display:none;">
                <p><b>Género:</b> <select autocomplete="off" class="form-control"
                        id="case_patient_sex_edit_input{id}">
                        <option value="">Sem Informação</option>
                        <option value="0">Masculino</option>
                        <option value="1">Feminino</option>
                    </select><a href="#" onclick="cancelUpdatePatientSex({id})">Cancelar</a>
                    <a href="#" onclick="submitUpdatePatientSex({id})">Atualizar</a></p>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="case_patient_DoB_display{id}">
                <p><b>Data de Nascimento:</b> <a href="#" onclick="updatePatientDoB()">{DoB}</a></p>
            </div>
            <div id="case_patient_DoB_edit{id}" class="form-inline" style="display:none;">
                <p><b>Data de Nascimento:</b> <input autocomplete="off" type="date"
                        id="case_patient_DoB_edit_input{id}" class="form-control"></p><a href="#"
                    onclick="cancelUpdatePatientDoB({id})">Cancelar</a> <a href="#"
                    onclick="submitUpdatePatientDoB({id})">Atualizar</a></p>
            </div>
        </div>
        <div class="col-sm-3">
            <p><b>Idade:</b> {age}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div id="case_patient_suspect_display{id}">
                <p><b>Caso Suspeito COVID-19:</b> <a href="#" onclick="updatePatientSuspect({id})">{suspect}</a></p>
            </div>
            <div id="case_patient_suspect_edit{id}" class="form-inline" style="display:none;">
                <p><b>Caso Suspeito COVID-19:</b> <select autocomplete="off" class="form-control"
                        id="case_patient_suspect_edit_input{id}" value="{suspect}">
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select></p><a href="#"
                    onclick="cancelUpdatePatientSuspect({id})">Cancelar</a> <a href="#"
                    onclick="submitUpdatePatientSuspect({id})">Atualizar</a></p>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="case_patient_suspect_validation_display{id}">
                <p><b>Validação:</b> <a href="#"
                        onclick="updatePatientSuspectValidation({id})"
                        id="case_patient_suspect_validation">{validation}</a></p>
            </div>
            <div id="case_patient_suspect_validation_edit{id}" class="form-inline"
                style="display:none;">
                <p><b>Validação:</b> <select autocomplete="off" class="form-control"
                        id="case_patient_suspect_validation_edit_input{id}" value="{validation}">
                        <option value="">Sem Informação</option>
                        <option value="CODU">CODU</option>
                        <option value="Equipa">Equipa</option>
                        <option value="Hospital">Hospital</option>
                    </select></p><a href="#"
                    onclick="cancelUpdatePatientSuspectValidation({id})">Cancelar</a> <a
                    href="#"
                    onclick="submitUpdatePatientSuspectValidation({id})">Atualizar</a></p>
            </div>
        </div>
        <div class="col-sm-6">
            <div id="case_patient_confirmed_display{id}">
                <p><b>Caso Confirmado COVID-19:</b> <a href="#" onclick="updatePatientConfirmed({id})">{confirmed}</a></p>
            </div>
            <div id="case_patient_confirmed_edit{id}" class="form-inline" style="display:none;">
                <p><b>Caso Confirmado COVID-19:</b> <select autocomplete="off" class="form-control"
                        id="case_patient_confirmed_edit_input" value="{confirmed}">
                        <option value="">Sem Informação</option>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select></p><a href="#"
                    onclick="cancelUpdatePatientConfirmed({id})">Cancelar</a> <a href="#"
                    onclick="submitUpdatePatientConfirmed({id})">Atualizar</a></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div id="case_patient_invasive_care_display{id}">
                <p><b>Realizados cuidados invasivos:</b> <a href="#"
                        onclick="updatePatientInvasiveCare({id})">{invasive_care}</a></p>
            </div>
            <div id="case_patient_invasive_care_edit{id}" class="form-inline"
                style="display:none;">
                <p><b>Realizados cuidados invasivos:</b> <select autocomplete="off"
                        class="form-control" id="case_patient_invasive_care_edit_input{id}" value="{invasive_care}">
                        <option value="">Sem Informação</option>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select></p><a href="#"
                    onclick="cancelUpdatePatientInvasiveCare({id})">Cancelar</a> <a href="#"
                    onclick="submitUpdatePatientInvasiveCare({id})">Atualizar</a></p>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-danger" onclick="removePatient({id})">Remover Vitima</button>
    <hr/>
</script>

<script type="covid19/template" id="team_member_template">
    <div class="row">
        <div class="col-sm-6">
            <div id="team_member_name_display{id}">
                <p><b>Nome:</b> <a href="#" onclick="updateTeamMemberName({id})">{name}</a></p>
            </div>
            <div id="team_member_name_edit{id}" class="form-inline" style="display:none;">
                <p><b>Nome:</b> <input autocomplete="off" type="text"
                        id="team_member_name_edit_input{id}" class="form-control" placeholder="Nome" value={name}> <a
                        href="#" onclick="cancelUpdateTeamMemberName({id})">Cancelar</a> <a href="#"
                        onclick="submitUpdateTeamMemberName({id})">Atualizar</a></p>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="team_member_age_display{id}">
                <p><b>Idade:</b> <a href="#" onclick="updateTeamMemberAge({id})">{age}</a></p>
            </div>
            <div id="team_member_age_edit{id}" class="form-inline" style="display:none;">
                <p><b>Idade:</b> <input autocomplete="off" type="text"
                        id="team_member_age_edit_input{id}" class="form-control" placeholder="Idade" value="{age}"> <a
                        href="#" onclick="cancelUpdateTeamMemberAge({id})">Cancelar</a> <a href="#"
                        onclick="submitUpdateTeamMemberAge({id})">Atualizar</a></p>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="team_member_contact_display{id}">
                <p><b>Contacto:</b> <a href="#" onclick="updateTeamMemberContact({id})">{contact}</a></p>
            </div>
            <div id="team_member_contact_edit{id}" class="form-inline" style="display:none;">
                <p><b>Contacto:</b> <input autocomplete="off" type="text"
                        id="team_member_contact_edit_input{id}" class="form-control"
                        placeholder="Contacto" value="{contact}"> <a href="#"
                        onclick="cancelUpdateTeamMemberContact({id})">Cancelar</a> <a href="#"
                        onclick="submitUpdateTeamMemberContact({id})">Atualizar</a></p>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="team_member_type_display{id}">
                <p><b>Tipo:</b> <a href="#" onclick="updateTeamMemberType({id})">{type}</a></p>
            </div>
            <div id="team_member_type_edit{id}" class="form-inline" style="display:none;">
                <p><b>Tipo:</b> <select class="form-control" id="team_member_type_edit_input{id}" placeholder="Tipo" value="{type}">
                    <option value="Condutor">Condutor</option>
                    <option value="Socorrista">Socorrista</option>
                </select> <a href="#"
                        onclick="cancelUpdateTeamMemberType({id})">Cancelar</a> <a href="#"
                        onclick="submitUpdateTeamMemberType({id})">Atualizar</a></p>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-danger" onclick="removeTeamMember({id})">Remover Membro da Equipa</button>
    <hr />
</script>
<script>
    let cases_history = false;
    function _calculateAge(birthday) {
        var ageDifMs = Date.now() - birthday.getTime();
        var ageDate = new Date(ageDifMs);
        return Math.abs(ageDate.getUTCFullYear() - 1970);
    }

    function getStatusTextFromNumber(status) {
        switch (status) {
            case 0:                
                return "INOP";
            case 1:
                return "Disponivel";
            case 2:
                return "Na Base";
            case 3:
                return "Accionamento";
            case 4:
                return "Caminho do local";
            case 5:
                return "No Local";
            case 6:
                return "Caminho do Destino";
            case 7:
                return "No Destino";
            case 8:
                return "Caminho da Base";
            case 9:
                return "Desinfecção";
            default:
                break;
        }
    }

    function getVehicleTypeFromNumber(vehicle_type) {
        switch (vehicle_type) {
            case 1:                
                return "COVID-19";
            case 2:
                return "SIEM-PEM";
            case 3:
                return "SIEM-RES";
            default:
                return "Informação Inválida";
        }
    }

    function getCODULocalizationFromNumber(status) {
        switch (status) {
            case 1:                
                return "Lisboa";
            case 2:
                return "Porto";
            case 3:
                return "Coimbra";
            case 4:
                return "Sala de Crise";
            default:
                break;
        }
    }

    function isOpenCaseCreated(open_case) {
        if($("#openCase"+open_case.id).length) {
            return true;
        }
        return false;
    }

    function createOpenCase(open_case) {
        if (open_case.CODU_number == null) {
            open_case.CODU_number = "Sem Número";
        }
        let template = $("#openCase_template").html();
        template = template.split("{id}").join(open_case.id);
        template = template.split("{CODU_number}").join(open_case.CODU_number);
        template = template.split("{activation_mean}").join(open_case.activation_mean);
        $("#open_cases").prepend(template);   
    }

    function updateOpenCase(open_case) {
        if (open_case.CODU_number == null) {
            open_case.CODU_number = "Sem Número";
        }
        $("#openCase"+open_case.id+" .CODU_number").html(open_case.CODU_number);
        $("#openCase"+open_case.id+" .activation_mean").html(open_case.activation_mean);
    }

    function removeOpenCase(open_case) {
        $("#openCase"+open_case.id).remove();
    }

    function fetchInitialOpenCases() {
        axios.get("{{route('covid19.openCases')}}")
            .then(function (response) {
                response.data.forEach(open_case => {
                    createOpenCase(open_case);                    
                });
            })
            .catch(function (error) {
                alert(error);
            })
    }
    
    function isAmbulanceCreated(ambulance) {
        if($("#ambulance"+ambulance.id).length) {
            return true;
        }
        return false;
    }

    function getAmbulanceCurrentStatus(ambulance) {
        return $("#ambulance"+ambulance.id).data('ambulance-status');
    }

    function createOnHoldAmbulance(ambulance) {
        let template = $("#ambulance_template").html();
        template = template.split("{id}").join(ambulance.id);
        template = template.split("{status}").join(ambulance.status);
        template = template.split("{structure}").join(ambulance.structure.toUpperCase());
        template = template.split("{region}").join(ambulance.region);
        template = template.split("{vehicle_identification}").join(ambulance.vehicle_identification);
        template = template.split("{status_text}").join(getStatusTextFromNumber(ambulance.status));
        $("#ambulances").prepend(template);
        if(ambulance.status == 1 || ambulance.status == 2) {
            if(document.getElementById("occorrence_ambulance_create_amb-"+ambulance.id) === null) {
                $("#occorrence_ambulance_create_amb").prepend('<option id="occorrence_ambulance_create_amb-'+ambulance.id+'" value="'+ambulance.id+'">'+ambulance.structure.toUpperCase()+' - '+ambulance.region+' - '+ambulance.vehicle_identification+'</option>');   
            }
        }
    }

    function createActiveAmbulance(ambulance) {
        axios.get("{{route('covid19.case','')}}/"+ambulance.current_case)
            .then(function (response) {
                if(response.data.street == null) {
                    response.data.street = "Sem Rua";
                }
                if(response.data.parish == null) {
                    response.data.parish = "Sem Freguesia";
                }
                if(response.data.county == null) {
                    response.data.county = "Sem Concelho";
                }
                if(response.data.district == null) {
                    response.data.district = "Sem Distrito";
                }
                if(response.data.CODU_number == null) {
                    response.data.CODU_number = "Sem Número";
                }
                let complete_source = response.data.street + ", " + response.data.parish + ", " + response.data.county + ", " + response.data.district;
                let template = $("#activeAmbulance_template").html();
                template = template.split("{id}").join(ambulance.id);
                template = template.split("{status}").join(ambulance.status);
                template = template.split("{structure}").join(ambulance.structure.toUpperCase());
                template = template.split("{region}").join(ambulance.region);
                template = template.split("{vehicle_identification}").join(ambulance.vehicle_identification);
                template = template.split("{status_text}").join(getStatusTextFromNumber(ambulance.status));
                template = template.split("{case_id}").join(ambulance.current_case);
                template = template.split("{codu_number}").join(response.data.CODU_number);
                template = template.split("{source}").join(complete_source);
                template = template.split("{destination}").join(response.data.destination);
                $("#active_ambulances").prepend(template);
            })
            .catch(function (error) {
                alert(error);
            })
    }

    function createAmbulance(ambulance) {
        if(ambulance.status > 2  && ambulance.status < 8) {
            createActiveAmbulance(ambulance);
        }
        else {
            createOnHoldAmbulance(ambulance);
        }
    }

    function updateOnHoldAmbulance(ambulance, old_status) {
        $("#ambulance"+ambulance.id+" .amb-structure").html(ambulance.structure.toUpperCase());
        $("#ambulance"+ambulance.id+" .amb-region").html(ambulance.region);
        $("#ambulance"+ambulance.id+" .amb-identification").html(ambulance.vehicle_identification);
        $("#ambulance"+ambulance.id+" .amb-status").html(getStatusTextFromNumber(ambulance.status));
        $("#ambulance"+ambulance.id+" .amb").removeClass('amb-'+old_status);
        $("#ambulance"+ambulance.id+" .amb").addClass('amb-'+ambulance.status);
        $("#ambulance"+ambulance.id).data('ambulance-status', ambulance.status);
        //Add or Remove Ambulance to Ambulance Activation Select
        if(ambulance.status == 1 || ambulance.status == 2) {
            if(document.getElementById("occorrence_ambulance_create_amb-"+ambulance.id) === null) {
                $("#occorrence_ambulance_create_amb").prepend('<option id="occorrence_ambulance_create_amb-'+ambulance.id+'" value="'+ambulance.id+'">'+ambulance.structure.toUpperCase()+' - '+ambulance.region+' - '+ambulance.vehicle_identification+'</option>');   
            }
        }
        else {
            $("#occorrence_ambulance_create_amb-"+ambulance.id).remove();
        }
        
    }

    function updateActiveAmbulance(ambulance,old_status) {
        axios.get("{{route('covid19.case','')}}/"+ambulance.current_case)
            .then(function (response) {
                updateOnHoldAmbulance(ambulance,old_status);
                if(response.data.street == null) {
                    response.data.street = "Sem Rua";
                }
                if(response.data.parish == null) {
                    response.data.parish = "Sem Freguesia";
                }
                if(response.data.county == null) {
                    response.data.county = "Sem Concelho";
                }
                if(response.data.district == null) {
                    response.data.district = "Sem Distrito";
                }
                if(response.data.CODU_number == null) {
                    response.data.CODU_number = "Sem Número";
                }
                let complete_source = response.data.street + ", " + response.data.parish + ", " + response.data.county + ", " + response.data.district;
                $("#ambulance"+ambulance.id+" .amb-codu-number").html(response.data.CODU_number);
                $("#ambulance"+ambulance.id+" .amb-source").html(complete_source);
                $("#ambulance"+ambulance.id+" .amb-destination").html(response.data.destination);
            })
            .catch(function (error) {
                alert(error);
            })
    }

    function updateAmbulance(ambulance) {
        let old_status = getAmbulanceCurrentStatus(ambulance);
        if(old_status == ambulance.status) {
            if(old_status > 2  && old_status < 8) {
                updateActiveAmbulance(ambulance,old_status);
            }
           else {
                updateOnHoldAmbulance(ambulance,old_status);
            }
        }
        else {
            if(ambulance.status > 2  && ambulance.status < 8) {
                if(old_status > 2  && old_status < 8) {
                    updateActiveAmbulance(ambulance,old_status);
                }
                else {
                    $("#ambulance"+ambulance.id).remove();
                    createActiveAmbulance(ambulance);
                }
            }
            else {
                if(!(ambulance.status > 2  && ambulance.status < 8)) {
                    $("#ambulance"+ambulance.id).remove();
                    createOnHoldAmbulance(ambulance);
                }
                else {
                    updateOnHoldAmbulance(ambulance,old_status);
                }
            }
        }
    }

    function fetchInitialAmbulances() {
        axios.get("{{route('covid19.ambulances')}}")
            .then(function (response) {
                response.data.forEach(ambulance => {
                    if(ambulance.status > 2  && ambulance.status < 8) {
                        createActiveAmbulance(ambulance);
                    }
                        else {
                        createAmbulance(ambulance);
                    }                     
                });
            })
            .catch(function (error) {
                alert(error);
            })
    }

    $(document).ready(function () {
        $("#all_cases").DataTable();
        $("#occorrence_ambulance_create_amb").change(function() {
            let val = $("#occorrence_ambulance_create_amb").val();
            if(val != "") {
                if(val == "SIEM-PEM" || val == "SIEM-RES") {
                    $("#occorrence_ambulance_create_amb_non_covid19").show();
                }
                else {
                    $("#occorrence_ambulance_create_amb_non_covid19").hide();
                }
            }
        });
        fetchInitialOpenCases();
        fetchInitialAmbulances();
        
    });
    $('#nova_ocorrencia_sem_numero').change(function() {
        if(this.checked) {
            $("#nova_ocorrencia_numero_codu").prop( "disabled", true );
        }
        else {
            $("#nova_ocorrencia_numero_codu").prop( "disabled", false );
        }
    });

    $('#nova_ocorrencia_sem_localizacao').change(function() {
        if(this.checked) {
            $("#nova_ocorrencia_localizacao_codu").prop( "disabled", true );
        }
        else {
            $("#nova_ocorrencia_localizacao_codu").prop( "disabled", false );
        }
    });

    function newAmbulance() {
        let structure = $("#nova_ambulancia_structure").val();
        let region = $("#nova_ambulancia_region").val();
        let vehicle_identification = $("#nova_ambulancia_vehicle_identification").val();
        let active_prevention = $("#nova_ambulancia_active_prevention").val();
        $("#nova_ambulancia_structure").val("");
        $("#nova_ambulancia_region").val("");
        $("#nova_ambulancia_vehicle_identification").val("");
        $("#nova_ambulancia_active_prevention").val("");
        axios.post("{{route('covid19.newAmbulance')}}", {
            structure: structure,
            region: region,
            vehicle_identification: vehicle_identification,
            active_prevention: active_prevention,
        })
        .then(function (response) {
            $('#nova_ambulancia').modal('toggle');
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function openCase(id) {
        let case_id = id;
        axios.get("{{route('covid19.case','')}}/"+case_id)
            .then(function (response) {
                if (response.data.CODU_number == null) {
                    response.data.CODU_number = "Sem Número";
                }
                if (response.data.CODU_localization == null) {
                    response.data.CODU_localization = "Sem Localização";
                }
                switch (response.data.CODU_localization) {
                    case 1:
                        response.data.CODU_localization = "Lisboa";
                        break;
                    case 2:
                        response.data.CODU_localization = "Porto";
                        break;
                    case 3:
                        response.data.CODU_localization = "Coimbra";
                        break;
                    case 4:
                        response.data.CODU_localization = "Sala de Crise";
                        break;
                    default:
                        response.data.CODU_localization = "Sem Localização";
                        break;
                }


                if(response.data.street == null) {
                    response.data.street = "Sem Informação";
                }
                if(response.data.parish == null) {
                    response.data.parish = "Sem Informação";
                }
                if(response.data.county == null) {
                    response.data.county = "Sem Informação";
                }
                if(response.data.district == null) {
                    response.data.district = "Sem Informação";
                }
                if(response.data.ref == null) {
                    response.data.ref = "Sem Informação";
                }
                if(response.data.source == null) {
                    response.data.source = "Sem Informação";
                }
                if(response.data.destination == null) {
                    response.data.destination = "Sem Informação";
                }
                if(response.data.doctor_responsible_on_scene == null) {
                    response.data.doctor_responsible_on_scene = "Sem Informação";
                }
                if(response.data.doctor_responsible_on_destination == null) {
                    response.data.doctor_responsible_on_destination = "Sem Informação";
                }
                if(response.data.on_scene_units == null) {
                    response.data.on_scene_units = "Sem Informação";
                }
                if(response.data.total_distance == null) {
                    response.data.total_distance = "Sem Informação";
                }
                if(response.data.structure == null) {
                    response.data.structure = "Sem Informação";
                }
                if(response.data.vehicle_identification == null) {
                    response.data.vehicle_identification = "Sem Informação";
                }
                if(response.data.vehicle_type == null) {
                    response.data.vehicle_type = "Sem Informação";
                }
                else {
                    response.data.vehicle_type = getVehicleTypeFromNumber(response.data.vehicle_type);
                }
                if(response.data.status_SALOP_activation == null) {
                    response.data.status_SALOP_activation = "Sem Informação";
                }
                if(response.data.status_AMB_activation == null) {
                    response.data.status_AMB_activation = "Sem Informação";
                }
                if(response.data.status_base_exit == null) {
                    response.data.status_base_exit = "Sem Informação";
                }
                if(response.data.status_arrival_on_scene == null) {
                    response.data.status_arrival_on_scene = "Sem Informação";
                }
                if(response.data.status_departure_from_scene == null) {
                    response.data.status_departure_from_scene = "Sem Informação";
                }
                if(response.data.status_arrival_on_destination == null) {
                    response.data.status_arrival_on_destination = "Sem Informação";
                }
                if(response.data.status_departure_from_destination == null) {
                    response.data.status_departure_from_destination = "Sem Informação";
                }
                if(response.data.status_base_return == null) {
                    response.data.status_base_return = "Sem Informação";
                }
                if(response.data.status_available == null) {
                    response.data.status_available = "Sem Informação";
                }
                
                $("#case_id").html(case_id);
                $("#activation_information_CODU_number").html(response.data.CODU_number);
                $("#activation_information_CODU_localization").html(response.data.CODU_localization);
                $("#activation_information_activation_mean").html(response.data.activation_mean);
                $("#event_street").html(response.data.street);
                $("#event_parish").html(response.data.parish);
                $("#event_county").html(response.data.county);
                $("#event_district").html(response.data.district);
                $("#event_ref").html(response.data.ref);
                $("#event_source").html(response.data.source);
                $("#event_destination").html(response.data.destination);
                $("#event_doctor_responsible_on_scene").html(response.data.doctor_responsible_on_scene);
                $("#event_doctor_responsible_on_destination").html(response.data.doctor_responsible_on_destination);
                $("#event_on_scene_units").html(response.data.on_scene_units);
                $("#event_total_distance").html(response.data.total_distance);
                $("#ambulance_structure").html(response.data.structure);
                $("#ambulance_vehicle_identification").html(response.data.vehicle_identification);
                $("#ambulance_vehicle_type").html(response.data.vehicle_type);
                $("#status_SALOP_activation").html(response.data.status_SALOP_activation);
                $("#status_AMB_activation").html(response.data.status_AMB_activation);
                $("#status_base_exit").html(response.data.status_base_exit);
                $("#status_arrival_on_scene").html(response.data.status_arrival_on_scene);
                $("#status_departure_from_scene").html(response.data.status_departure_from_scene);
                $("#status_arrival_on_destination").html(response.data.status_arrival_on_destination);
                $("#status_departure_from_destination").html(response.data.status_departure_from_destination);
                $("#status_base_return").html(response.data.status_base_return);
                $("#status_available").html(response.data.status_available);
                $("#case_notes_textarea").val(response.data.notes);

                if(response.data.source == "Sem Informação") {
                    $("#occorrence_data").hide();
                    $("#occorrence_data_create").show();
                }
                else {
                    $("#occorrence_data_create").hide();
                    $("#occorrence_data").show();
                }

                $("#status_AMB_activation_display").hide();
                $("#status_base_exit_display").hide();
                $("#status_arrival_on_scene_display").hide();
                $("#status_departure_from_scene_display").hide();
                $("#status_arrival_on_destination_display").hide();
                $("#status_departure_from_destination_display").hide();
                $("#status_base_return_display").hide();
                $("#status_available_display").hide();

                if(response.data.source == "Sem Informação") {
                    $("#case_ambulance").hide();
                    $("#case_team").hide();
                }
                else {
                    $("#case_ambulance").show();
                    if(response.data.status_AMB_activation == "Sem Informação") {
                        $("#occorrence_ambulance").hide();
                        $("#occorrence_ambulance_create").show();
                        $("#case_team").hide();                        
                    }
                    else {
                        $("#occorrence_ambulance_create").hide();
                        $("#occorrence_ambulance").show();
                        $("#status_AMB_activation_display").show();
                        $("#case_team").show();
                    }
                }

                if(response.data.status_base_exit != "Sem Informação" || response.data.status_base_exit) {
                    $("#status_base_exit_display").show();
                }

                if(response.data.status_arrival_on_scene != "Sem Informação") {
                    $("#status_arrival_on_scene_display").show();
                }

                if(response.data.status_departure_from_scene != "Sem Informação") {
                    $("#status_departure_from_scene_display").show();
                }

                if(response.data.status_arrival_on_destination != "Sem Informação") {
                    $("#status_arrival_on_destination_display").show();
                }

                if(response.data.status_departure_from_destination != "Sem Informação") {
                    $("#status_departure_from_destination_display").show();
                }

                if(response.data.status_base_return != "Sem Informação") {
                    $("#status_base_return_display").show();
                }

                if(response.data.status_available != "Sem Informação") {
                    $("#status_available_display").show();
                }

                if(response.data.vehicle_type == "SIEM-PEM" || response.data.vehicle_type == "SIEM-RES") {
                    $("#status_SALOP_activation_display").show();
                    $("#status_AMB_activation_display").show();
                    $("#status_base_exit_display").show();
                    $("#status_arrival_on_scene_display").show();
                    $("#status_departure_from_scene_display").show();
                    $("#status_arrival_on_destination_display").show();
                    $("#status_departure_from_destination_display").show();
                    $("#status_base_return_display").show();
                    $("#status_available_display").show();
                }

                if(cases_history) {
                    $("#status_SALOP_activation_display").show();
                    $("#status_AMB_activation_display").show();
                    $("#status_base_exit_display").show();
                    $("#status_arrival_on_scene_display").show();
                    $("#status_departure_from_scene_display").show();
                    $("#status_arrival_on_destination_display").show();
                    $("#status_departure_from_destination_display").show();
                    $("#status_base_return_display").show();
                    $("#status_available_display").show();
                    $("#occorrence_ambulance_create").hide();
                    $("#occorrence_ambulance").show();
                    $("#case_team").show();
                    $("#occorrence_data_create").hide();
                    $("#occorrence_data").show();
                }

                if(response.data.status_available != "Sem Informação") {
                    $("#case_notes").hide();
                }
                else {
                    $("#case_notes").show();
                }

                axios.get("{{route('covid19.case_patients','')}}/"+case_id)
                    .then(function (response) {
                        $("#occorrence_patient").html("<p>Sem Dados de Vitimas</p>");
                        if(response.data.length > 0) {
                            $("#occorrence_patient").html("");
                        }
                        response.data.forEach(patient => {
                            let template = $("#patient_template").html();
                            template = template.split("{id}").join(patient.id);
                            template = template.split("{RNU}").join(patient.RNU == null ? "Sem Informação": patient.RNU);
                            template = template.split("{firstname}").join(patient.firstname == null ? "Sem Informação": patient.firstname);
                            template = template.split("{lastname}").join(patient.lastname == null ? "Sem Informação": patient.lastname);
                            template = template.split("{sex}").join(patient.sex == 1 ? "Feminino": "Masculino");
                            template = template.split("{DoB}").join(patient.DoB == null ? "Sem Informação": patient.DoB);
                            let age = "Sem Informação";
                            if(patient.DoB != null) {
                                let DoB_date = new Date(patient.DoB);
                                age = _calculateAge(DoB_date);
                            }
                            template = template.split("{age}").join(age);
                            template = template.split("{suspect}").join(patient.suspect == 1 ? "Sim":"Não");
                            template = template.split("{validation}").join(patient.suspect_validation == null ? "": patient.suspect_validation);
                            template = template.split("{confirmed}").join(patient.confirmed == 1 ? "Sim":"Não");
                            template = template.split("{invasive_care}").join(patient.invasive_care == 1 ? "Sim":"Não");
                            $("#occorrence_patient").append(template);
                        });
                        $("#case").modal('show');
                    })
                    .catch(function (error) {
                        alert(error);
                    })


                axios.get("{{route('covid19.case_team_members','')}}/"+case_id)
                    .then(function (response) {
                        $("#occorrence_team").html("<p>Sem Membros de Equipa</p>");
                        if(response.data.length > 0) {
                            $("#occorrence_team").html("");
                        }
                        response.data.forEach(team_member => {
                            let template = $("#team_member_template").html();
                            template = template.split("{id}").join(team_member.id);
                            template = template.split("{name}").join(team_member.name == null ? "Sem Informação": team_member.name);
                            template = template.split("{age}").join(team_member.age == null ? "Sem Informação": team_member.age);
                            template = template.split("{contact}").join(team_member.contact == null ? "Sem Informação": team_member.contact);
                            template = template.split("{type}").join(team_member.type);
                            $("#occorrence_team").append(template);
                        });
                        $("#case").modal('show');
                    })
                    .catch(function (error) {
                        alert(error);
                    })

                /*axios.get("{{route('covid19.ambulance_team_members','')}}/"+response.data.ambulance_id)
                    .then(function (response) {
                        let team_members = Object.values(response.data);
                        $( "#case_team_name" ).autocomplete({
                            source: team_members
                        });
                    })
                    .catch(function (error) {
                        alert(error);
                    })*/
                    

                axios.get("{{route('covid19.case_operators','')}}/"+case_id)
                    .then(function (response) {
                        $("#case_operators_inside").html("<p>Sem Operadores Registados</p>");
                        if(response.data.length > 0) {
                            $("#case_operators_inside").html("");
                        }
                        response.data.forEach(operator => {
                            $("#case_operators_inside").append('<p>'+operator+'</p>');
                        });
                        $("#case").modal('show');
                    })
                    .catch(function (error) {
                        alert(error);
                    })

                axios.get("{{route('covid19.case_observations','')}}/"+case_id)
                    .then(function (response) {
                        $("#case_observations_inside").html("<p>Sem Observações</p>");
                        if(response.data.length > 0) {
                            $("#case_observations_inside").html("");
                        }
                        response.data.forEach(observation => {
                            let template = $("#observation_template").html();
                            template = template.split("{id}").join(observation.id);
                            template = template.split("{author}").join(observation.author_name);
                            template = template.split("{date}").join(observation.created_at);
                            template = template.split("{observation}").join(observation.observation);
                            $("#case_observations_inside").append(template);
                        });
                        $("#case").modal('show');
                    })
                    .catch(function (error) {
                        alert(error);
                    })

            })
            .catch(function (error) {
                alert(error);
            })
    
    }

    function closeCase() {
        $("#case").modal('hide');
    }

    function openAmbulance(id) {
        let ambulance_id = id;
        axios.get("{{route('covid19.ambulance','')}}/"+ambulance_id)
            .then(function (response) {
                $("#ambulance_id").html(ambulance_id);
                $("#open_ambulance_structure").html(response.data.structure);
                $("#open_ambulance_region").html(response.data.region);
                $("#open_ambulance_vehicle_identification").html(response.data.vehicle_identification);
                $("#open_ambulance_active_prevention").html(response.data.active_prevention ? "Prevenção Ativa": "Prevenção Passiva");
                $("#open_ambulance_status").html(getStatusTextFromNumber(response.data.status));
                $("#open_ambulance_status_date").html(response.data.status_date);
                $("#ambulance-open-case-button").hide();
                $("#ambulance-inop-button").hide();
                $("#ambulance-available-button").hide();
                $("#ambulance-on-base-button").hide();
                $("#ambulance-base-exit-button").hide();
                $("#ambulance-arrival-on-scene-button").hide();
                $("#ambulance-departure-from-scene-button").hide();
                $("#ambulance-arrival-on-destination-button").hide();
                $("#ambulance-departure-from-destination-button").hide();
                $("#ambulance-base-return-button").hide();
                if(response.data.status == 0) {
                    $("#ambulance-available-button").show();
                    $("#ambulance-on-base-button").show();
                }
                else if(response.data.status == 1 ) {
                    $("#ambulance-inop-button").show();
                    $("#ambulance-on-base-button").show();
                }
                else if(response.data.status == 2) {
                    $("#ambulance-inop-button").show();
                    $("#ambulance-available-button").show();
                }
                else if(response.data.status == 3) {
                    $("#ambulance-base-exit-button").show();
                }
                else if(response.data.status == 4) {
                    $("#ambulance-base-exit-button").show();
                    $("#ambulance-arrival-on-scene-button").show();
                }
                else if(response.data.status == 5) {
                    $("#ambulance-base-exit-button").show();
                    $("#ambulance-departure-from-scene-button").show();
                }
                else if(response.data.status == 6) {
                    $("#ambulance-arrival-on-scene-button").show();
                    $("#ambulance-arrival-on-destination-button").show();
                }
                else if(response.data.status == 7) {
                    $("#ambulance-departure-from-scene-button").show();
                    $("#ambulance-departure-from-destination-button").show();
                }
                else if(response.data.status == 8) {
                    $("#ambulance-arrival-on-destination-button").show();
                    $("#ambulance-base-return-button").show();
                }
                else if(response.data.status == 9) {
                    $("#ambulance-departure-from-destination-button").show();
                    $("#ambulance-inop-button").show();
                    $("#ambulance-available-button").show();
                    $("#ambulance-on-base-button").show();
                }
                if(response.data.current_case != null) {
                    $("#ambulance-open-case-button").show();
                    $("#ambulance-open-case-button").attr("onclick","closeAmbulance();openCase("+response.data.current_case+")");
                }
                axios.get("{{route('covid19.ambulance_contacts','')}}/"+ambulance_id)
                    .then(function (response) {
                        $("#ambulance_contacts_inside").html("<p>Sem Contactos Registados</p>");
                        if(response.data.length > 0) {
                            $("#ambulance_contacts_inside").html("");
                        }
                        response.data.forEach(contact => {
                            $("#ambulance_contacts_inside").append('<p>'+contact.contact+' - '+contact.name+' - '+(contact.sms?"Envio de SMS Automático":"Sem Envio de SMS Automático")+' - <a href="#" onclick="removeContact('+contact.id+')">Remover</a></p>');
                        });
                        $("#ambulance").modal('show');
                    })
                    .catch(function (error) {
                        alert(error);
                    })
                $("#ambulance").modal('show');
             })
            .catch(function (error) {
                alert(error);
            })    
    }
    
    function closeAmbulance() {
        $("#ambulance").modal('hide');
    }

    function openDataInsert() {
        $("#occorence_data_create_validate").hide();
        $("#occorence_data_create_insert").show();
    }


    function insertPatient() {
        let id = $("#case_id").html();
        let RNU = $("#case_patient_RNU").val();
        let firstname = $("#case_patient_firstname").val();
        let lastname = $("#case_patient_lastname").val();
        let genero = $("#case_patient_genero").val();
        let DoB = $("#case_patient_DoB").val();
        let suspect = $("#case_patient_suspect").val();
        let suspect_validation = $("#case_patient_suspect_validation").val();
        let confirmed = $("#case_patient_confirmed").val();
        let invasive_care = $("#case_patient_invasive_care").val();
        if(RNU == "") {
            RNU = null;
        }
        if(firstname == "") {
            firstname = null;
        }
        if(lastname == "") {
            lastname = null;
        }
        if(genero == "") {
            genero = null;
        }
        if(DoB == "") {
            DoB = null;
        }
        if(suspect_validation == "") {
            suspect_validation = null;
        }
        if(confirmed == "") {
            confirmed = null;
        }
        if(invasive_care == "") {
            invasive_care = null;
        }
        $("#case_patient_RNU").val("");
        $("#case_patient_firstname").val("");
        $("#case_patient_lastname").val("");
        $("#case_patient_genero").val("");
        $("#case_patient_DoB").val("");
        $("#case_patient_suspect").val("1");
        $("#case_patient_suspect_validation").val("");
        $("#case_patient_confirmed").val("");
        $("#case_patient_invasive_care").val("");
        axios.post("{{route('covid19.insertPatient')}}", {
            id: id,
            RNU: RNU,
            firstname: firstname,
            lastname: lastname,
            sex: genero,
            DoB: DoB,
            suspect: suspect,
            suspect_validation: suspect_validation,
            confirmed: confirmed,
            invasive_care: invasive_care
        })
        .then(function (response) {

        })
        .catch(function (error) {
            alert(error);
        });

    }

    function submitDataInsert() {
        let id = $("#case_id").html();
        let street = $("#occorence_data_create_street").val();
        let ref = $("#occorence_data_create_ref").val();
        let parish = $("#occorence_data_create_parish").val();
        let county = $("#occorence_data_create_county").val();
        let district = $("#occorence_data_create_district").val();
        let origem = $("#occorence_data_create_origem").val();
        let origem_specify = $("#occorence_data_create_origem_specify").val();
        let destino = $("#occorence_data_create_destino").val();
        let doctor_responsible_on_scene = $("#occorence_data_create_medico_local").val();
        let doctor_responsible_on_destination = $("#occorence_data_create_medico_destino").val();
        let outros_meios = $("#occorence_data_create_outros_meios").val();
        let outros_meios_specify = $("#occorence_data_create_outros_meios_specify").val();
        let distancia_total = $("#occorence_data_create_distancia_total").val();
        if(street == "") {
            street = null;
        }
        if(ref == "") {
            ref = null;
        }
        if(parish == "") {
            parish = null;
        }
        if(county == "") {
            county = null;
        }
        if(district == "") {
            district = null;
        }
        origem = origem + " - " + origem_specify;
        if(destino == "") {
            destino = null;
        }
        if(doctor_responsible_on_scene == "") {
            doctor_responsible_on_scene = null;
        }
        if(doctor_responsible_on_destination == "") {
            doctor_responsible_on_destination = null;
        }
        if(outros_meios == "") {
            outros_meios = null;
        }
        else if(outros_meios == "1") {
            outros_meios = "Sim" + " - " + outros_meios_specify;
        }
        else {
            outros_meios = "Não";
        }
        if(distancia_total == "") {
            distancia_total = null;
        }
        axios.post("{{route('covid19.insertEvent')}}", {
            id: id,
            street: street,
            ref: ref,
            parish: parish,
            county: county,
            district: district,
            source: origem,
            destination: destino,
            doctor_responsible_on_scene: doctor_responsible_on_scene,
            doctor_responsible_on_destination: doctor_responsible_on_destination,
            on_scene_units: outros_meios,
            total_distance: distancia_total
        })
        .then(function (response) {
            cancelDataInsert();
            closeCase();
            openCase(id);
        })
        .catch(function (error) {
            alert(error);
        });

    }

    function cancelDataInsert() {
        $("#occorence_data_create_validate").show();
        $("#occorence_data_create_insert").hide();
    }

    $('#activation_information_CODU_number_update_sem_numero').change(function() {
        if(this.checked) {
            $("#activation_information_CODU_number_update").prop( "disabled", true );
        }
        else {
            $("#activation_information_CODU_number_update").prop( "disabled", false );
        }
    });

    $('#activation_information_CODU_localization_update_sem_localizacao').change(function() {
        if(this.checked) {
            $("#activation_information_CODU_localization_update").prop( "disabled", true );
        }
        else {
            $("#activation_information_CODU_localization_update").prop( "disabled", false );
        }
    });

    function newCase() {
        let CODU_number = 0;
        let CODU_localization = 0;
        let activation_mean = "";
        if($('#nova_ocorrencia_sem_numero').is(':checked')) {
            CODU_number = -1;
        }
        else {
            CODU_number = $("#nova_ocorrencia_numero_codu").val();
        }

        if($('#nova_ocorrencia_sem_localizacao').is(':checked')) {
            CODU_localization = -1;
        }
        else {
            CODU_localization = $("#nova_ocorrencia_localizacao_codu").val();
        }
        activation_mean = $("#nova_ocorrencia_activation_mean").val() + " - " + $("#nova_ocorrencia_activation_mean_specify").val();
        $("#nova_ocorrencia_numero_codu").val("");
        $("#nova_ocorrencia_numero_codu").prop( "disabled", false );
        $("#nova_ocorrencia_sem_numero").prop( "checked", false );
        $("#nova_ocorrencia_localizacao_codu").val("1");
        $("#nova_ocorrencia_localizacao_codu").prop( "disabled", false );
        $("#nova_ocorrencia_sem_localizacao").prop( "checked", false );
        $("#nova_ocorrencia_activation_mean").val("");
        $("#nova_ocorrencia_activation_mean_specify").val("");
        axios.post("{{route('covid19.newCase')}}", {
            CODU_number: CODU_number,
            CODU_localization: CODU_localization,
            activation_mean: activation_mean,
        })
        .then(function (response) {
            $('#nova_ocorrencia').modal('toggle');
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateActivationInformationCODUnumber() {
        $("#activation_information_CODU_number_display").hide();
        $("#activation_information_CODU_number_edit").show();
    }

    function cancelUpdateActivationInformationCODUnumber() {
        $("#activation_information_CODU_number_edit").hide();
        $("#activation_information_CODU_number_display").show();
    }

    function submitUpdateActivationInformationCODUnumber() {
        let id = $("#case_id").html();
        let CODU_number = 0;
        let CODU_number_display = "";
        if($('#activation_information_CODU_number_update_sem_numero').is(':checked')) {
            CODU_number = -1;
            CODU_number_display = "Sem Número";
        }
        else {
            CODU_number = $("#activation_information_CODU_number_update").val();
            CODU_number_display = CODU_number;
        }
        $("#activation_information_CODU_number").html(CODU_number_display);
        $("#activation_information_CODU_number_update").val("");
        $("#activation_information_CODU_number_update").prop( "disabled", false );
        $('#activation_information_CODU_number_update_sem_numero').prop( "checked", false );
        cancelUpdateActivationInformationCODUnumber();
        axios.post("{{route('covid19.updateCODUNumber')}}", {
            id: id,
            CODU_number: CODU_number,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateActivationInformationCODUlocalization() {
        $("#activation_information_CODU_localization_display").hide();
        $("#activation_information_CODU_localization_edit").show();
    }

    function cancelUpdateActivationInformationCODUlocalization() {
        $("#activation_information_CODU_localization_edit").hide();
        $("#activation_information_CODU_localization_display").show();
    }

    function submitUpdateActivationInformationCODUlocalization() {
        let id = $("#case_id").html();
        let CODU_localization = 0;
        let CODU_localization_display = "";
        if($('#activation_information_CODU_localization_update_sem_localizacao').is(':checked')) {
            CODU_localization = -1;
        }
        else {
            CODU_localization = $("#activation_information_CODU_localization_update").val();
        }
        $("#activation_information_CODU_localization_update").val("1");
        $("#activation_information_CODU_localization_update").prop( "disabled", false );
        $('#activation_information_CODU_localization_update_sem_localizacao').prop( "checked", false );
        switch (CODU_localization) {
            case "1":
                CODU_localization_display = "Lisboa";
                break;
            case "2":
                CODU_localization_display = "Porto";
                break;
            case "3":
                CODU_localization_display = "Coimbra";
                break;
            case "4":
                CODU_localization_display = "Sala de Crise";
                break;
            default:
                CODU_localization_display = "Sem Localização";
                break;
        }
        $("#activation_information_CODU_localization").html(CODU_localization_display);
        cancelUpdateActivationInformationCODUlocalization();
        axios.post("{{route('covid19.updateCODULocalization')}}", {
            id: id,
            CODU_localization: CODU_localization,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateActivationInformationActivationMean() {
        $("#activation_information_activation_mean_display").hide();
        $("#activation_information_activation_mean_edit").show();
    }

    function cancelUpdateActivationInformationActivationMean() {
        $("#activation_information_activation_mean_edit").hide();
        $("#activation_information_activation_mean_display").show();
    }

    function submitUpdateActivationInformationActivationMean() {
        let id = $("#case_id").html();
        let activation_mean = $("#activation_information_activation_mean_update").val() + " - " + $("#activation_information_activation_mean_update_specify").val();
        $("#activation_information_activation_mean").html(activation_mean);
        $("#activation_information_activation_mean_update").val("CNE");
        $("#activation_information_activation_mean_update_specify").val("");
        cancelUpdateActivationInformationActivationMean();
        axios.post("{{route('covid19.updateActivationMean')}}", {
            id: id,
            activation_mean: activation_mean,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientRNU(patient_id) {
        $("#case_patient_RNU_display"+patient_id).hide();
        $("#case_patient_RNU_edit"+patient_id).show();
    }

    function cancelUpdatePatientRNU(patient_id) {
        $("#case_patient_RNU_edit"+patient_id).hide();
        $("#case_patient_RNU_display"+patient_id).show();
    }

    function submitUpdatePatientRNU(patient_id) {
        let id = $("#case_id").html();
        let rnu = $("#case_patient_RNU_edit_input"+patient_id).val();
        if(rnu == "") {
            rnu = null;
        }
        cancelUpdatePatientRNU(patient_id);
        axios.post("{{route('covid19.updatePatientRNU')}}", {
            id: id,
            patient_id: patient_id,
            rnu: rnu,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientFirstname(patient_id) {
        $("#case_patient_firstname_display"+patient_id).hide();
        $("#case_patient_firstname_edit"+patient_id).show();
    }

    function cancelUpdatePatientFirstname(patient_id) {
        $("#case_patient_firstname_edit"+patient_id).hide();
        $("#case_patient_firstname_display"+patient_id).show();
    }

    function submitUpdatePatientFirstname(patient_id) {
        let id = $("#case_id").html();
        let firstname = $("#case_patient_firstname_edit_input"+patient_id).val();
        $("#case_patient_firstname_edit_input"+patient_id).val("");
        if(firstname == "") {
            firstname = null;
        }
        cancelUpdatePatientFirstname(patient_id);
        axios.post("{{route('covid19.updatePatientFirstname')}}", {
            id: id,
            patient_id: patient_id,
            firstname: firstname,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientLastname(patient_id) {
        $("#case_patient_lastname_display"+patient_id).hide();
        $("#case_patient_lastname_edit"+patient_id).show();
    }

    function cancelUpdatePatientLastname(patient_id) {
        $("#case_patient_lastname_edit"+patient_id).hide();
        $("#case_patient_lastname_display"+patient_id).show();
    }

    function submitUpdatePatientLastname(patient_id) {
        let id = $("#case_id").html();
        let lastname = $("#case_patient_lastname_edit_input"+patient_id).val();
        $("#case_patient_lastname_edit_input"+patient_id).val("");
        if(lastname == "") {
            lastname = null;
        }
        cancelUpdatePatientLastname(patient_id);
        axios.post("{{route('covid19.updatePatientLastname')}}", {
            id: id,
            patient_id: patient_id,
            lastname: lastname,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientSex(patient_id) {
        $("#case_patient_sex_display"+patient_id).hide();
        $("#case_patient_sex_edit"+patient_id).show();
    }

    function cancelUpdatePatientSex(patient_id) {
        $("#case_patient_sex_edit"+patient_id).hide();
        $("#case_patient_sex_display"+patient_id).show();
    }

    function submitUpdatePatientSex(patient_id) {
        let id = $("#case_id").html();
        let sex = $("#case_patient_sex_edit_input"+patient_id).val();
        $("#case_patient_sex_edit_input"+patient_id).val("");
        let sex_display = "";
        if(sex == "") {
            sex = null;
        }
        $("#case_patient_sex").html(sex_display);
        cancelUpdatePatientSex(patient_id);
        axios.post("{{route('covid19.updatePatientSex')}}", {
            id: id,
            patient_id: patient_id,
            sex: sex,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientDoB(patient_id) {
        $("#case_patient_DoB_display"+patient_id).hide();
        $("#case_patient_DoB_edit"+patient_id).show();
    }

    function cancelUpdatePatientDoB(patient_id) {
        $("#case_patient_DoB_edit"+patient_id).hide();
        $("#case_patient_DoB_display"+patient_id).show();
    }

    function submitUpdatePatientDoB(patient_id) {
        let id = $("#case_id").html();
        let DoB = $("#case_patient_DoB_edit_input"+patient_id).val();
        $("#case_patient_DoB_edit_input"+patient_id).val("");
        if(DoB == "") {
            DoB = null;
        }
        cancelUpdatePatientDoB(patient_id);
        axios.post("{{route('covid19.updatePatientDoB')}}", {
            id: id,
            patient_id: patient_id,
            dob: DoB,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientSuspect(patient_id) {
        $("#case_patient_suspect_display"+patient_id).hide();
        $("#case_patient_suspect_edit"+patient_id).show();
    }

    function cancelUpdatePatientSuspect(patient_id) {
        $("#case_patient_suspect_edit"+patient_id).hide();
        $("#case_patient_suspect_display"+patient_id).show();
    }

    function submitUpdatePatientSuspect(patient_id) {
        let id = $("#case_id").html();
        let suspect = $("#case_patient_suspect_edit_input"+patient_id).val();
        $("#case_patient_suspect_edit_input"+patient_id).val("");  
        cancelUpdatePatientSuspect(patient_id);
        axios.post("{{route('covid19.updatePatientSuspect')}}", {
            id: id,
            patient_id: patient_id,
            suspect: suspect,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientSuspectValidation(patient_id) {
        $("#case_patient_suspect_validation_display"+patient_id).hide();
        $("#case_patient_suspect_validation_edit"+patient_id).show();
    }

    function cancelUpdatePatientSuspectValidation(patient_id) {
        $("#case_patient_suspect_validation_edit"+patient_id).hide();
        $("#case_patient_suspect_validation_display"+patient_id).show();
    }

    function submitUpdatePatientSuspectValidation(patient_id) {
        let id = $("#case_id").html();
        let suspect_validation = $("#case_patient_suspect_validation_edit_input"+patient_id).val();
        $("#case_patient_suspect_validation_edit_input"+patient_id).val("");
        if(suspect_validation == "") {
            suspect_validation = null;
        }   
        cancelUpdatePatientSuspectValidation(patient_id);
        axios.post("{{route('covid19.updatePatientSuspectValidation')}}", {
            id: id,
            patient_id: patient_id,
            suspect_validation: suspect_validation,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientConfirmed(patient_id) {
        $("#case_patient_confirmed_display"+patient_id).hide();
        $("#case_patient_confirmed_edit"+patient_id).show();
    }

    function cancelUpdatePatientConfirmed(patient_id) {
        $("#case_patient_confirmed_edit"+patient_id).hide();
        $("#case_patient_confirmed_display"+patient_id).show();
    }

    function submitUpdatePatientConfirmed(patient_id) {
        let id = $("#case_id").html();
        let confirmed = $("#case_patient_confirmed_edit_input"+patient_id).val();
        $("#case_patient_confirmed_edit_input"+patient_id).val("");
        if(confirmed == "") {
            confirmed = null;
        }   
        cancelUpdatePatientConfirmed(patient_id);
        axios.post("{{route('covid19.updatePatientConfirmed')}}", {
            id: id,
            patient_id: patient_id,
            confirmed: confirmed,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updatePatientInvasiveCare(patient_id) {
        $("#case_patient_invasive_care_display"+patient_id).hide();
        $("#case_patient_invasive_care_edit"+patient_id).show();
    }

    function cancelUpdatePatientInvasiveCare(patient_id) {
        $("#case_patient_invasive_care_edit"+patient_id).hide();
        $("#case_patient_invasive_care_display"+patient_id).show();
    }

    function submitUpdatePatientInvasiveCare(patient_id) {
        let id = $("#case_id").html();
        let invasive_care = $("#case_patient_invasive_care_edit_input"+patient_id).val();
        $("#case_patient_invasive_care_edit_input"+patient_id+patient_id).val("");
        if(invasive_care == "") {
            invasive_care = null;
        }    
        cancelUpdatePatientInvasiveCare(patient_id);
        axios.post("{{route('covid19.updatePatientInvasiveCare')}}", {
            id: id,
            patient_id: patient_id,
            invasive_care: invasive_care,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function removePatient(patient_id) {
        let case_id = $("#case_id").html();
        axios.post("{{route('covid19.removePatient')}}", {
            id: case_id,
            patient_id: patient_id,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventStreet() {
        $("#event_street_display").hide();
        $("#event_street_edit").show();
    }

    function cancelUpdateEventStreet() {
        $("#event_street_edit").hide();
        $("#event_street_display").show();
    }

    function submitUpdateEventStreet() {
        let id = $("#case_id").html();
        let street = $("#event_street_edit_input").val();
        $("#event_street_edit_input").val("");
        if(street == "") {
            $("#event_street").html("Sem Informação");
            street = null;
        }
        else {
            $("#event_street").html(street);
        }
        cancelUpdateEventStreet();
        axios.post("{{route('covid19.updateStreet')}}", {
            id: id,
            street: street,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventRef() {
        $("#event_ref_display").hide();
        $("#event_ref_edit").show();
    }

    function cancelUpdateEventRef() {
        $("#event_ref_edit").hide();
        $("#event_ref_display").show();
    }

    function submitUpdateEventRef() {
        let id = $("#case_id").html();
        let ref = $("#event_ref_edit_input").val();
        $("#event_ref_edit_input").val("");
        if(ref == "") {
            $("#event_ref").html("Sem Informação");
            ref = null;
        }
        else {
            $("#event_ref").html(ref);
        }
        cancelUpdateEventRef();
        axios.post("{{route('covid19.updateRef')}}", {
            id: id,
            ref: ref,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventParish() {
        $("#event_parish_display").hide();
        $("#event_parish_edit").show();
    }

    function cancelUpdateEventParish() {
        $("#event_parish_edit").hide();
        $("#event_parish_display").show();
    }

    function submitUpdateEventParish() {
        let id = $("#case_id").html();
        let parish = $("#event_parish_edit_input").val();
        $("#event_parish_edit_input").val("");
        if(parish == "") {
            $("#event_parish").html("Sem Informação");
            parish = null;
        }
        else {
            $("#event_parish").html(parish);
        }
        cancelUpdateEventParish();
        axios.post("{{route('covid19.updateParish')}}", {
            id: id,
            parish: parish,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventCounty() {
        $("#event_county_display").hide();
        $("#event_county_edit").show();
    }

    function cancelUpdateEventCounty() {
        $("#event_county_edit").hide();
        $("#event_county_display").show();
    }

    function submitUpdateEventCounty() {
        let id = $("#case_id").html();
        let county = $("#event_county_edit_input").val();
        $("#event_county_edit_input").val("");
        if(county == "") {
            $("#event_county").html("Sem Informação");
            county = null;
        }
        else {
            $("#event_county").html(county);
        }
        cancelUpdateEventCounty();
        axios.post("{{route('covid19.updateCounty')}}", {
            id: id,
            county: county,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventDistrict() {
        $("#event_district_display").hide();
        $("#event_district_edit").show();
    }

    function cancelUpdateEventDistrict() {
        $("#event_district_edit").hide();
        $("#event_district_display").show();
    }

    function submitUpdateEventDistrict() {
        let id = $("#case_id").html();
        let district = $("#event_district_edit_input").val();
        $("#event_district_edit_input").val("")
        if(district == "") {
            $("#event_district").html("Sem Informação");
            district = null;
        }
        else {
            $("#event_district").html(district);
        }
        cancelUpdateEventDistrict();
        axios.post("{{route('covid19.updateDistrict')}}", {
            id: id,
            district: district,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventSource() {
        $("#event_source_display").hide();
        $("#event_source_edit").show();
    }

    function cancelUpdateEventSource() {
        $("#event_source_edit").hide();
        $("#event_source_display").show();
    }

    function submitUpdateEventSource() {
        let id = $("#case_id").html();
        let source = $("#event_source_edit_input").val() + " - " + $("#event_source_edit_input_specify").val();
        $("#event_source_edit_input").val("Domicílio");
        $("#event_source_edit_input_specify").val("");
        $("#event_source").html(source);
        cancelUpdateEventSource();
        axios.post("{{route('covid19.updateSource')}}", {
            id: id,
            source: source,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventDestination() {
        $("#event_destination_display").hide();
        $("#event_destination_edit").show();
    }

    function cancelUpdateEventDestination() {
        $("#event_destination_edit").hide();
        $("#event_destination_display").show();
    }

    function submitUpdateEventDestination() {
        let id = $("#case_id").html();
        let destination = $("#event_destination_edit_input").val();
        if(destination == "") {
            $("#event_destination").html("Sem Informação");
            destination = null;
        }
        else {
            $("#event_destination").html(destination);
        }
        cancelUpdateEventDestination();
        axios.post("{{route('covid19.updateDestination')}}", {
            id: id,
            destination: destination,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventDoctorResponsibleOnScene() {
        $("#event_doctor_responsible_on_scene_display").hide();
        $("#event_doctor_responsible_on_scene_edit").show();
    }

    function cancelUpdateEventDoctorResponsibleOnScene() {
        $("#event_doctor_responsible_on_scene_edit").hide();
        $("#event_doctor_responsible_on_scene_display").show();
    }

    function submitUpdateEventDoctorResponsibleOnScene() {
        let id = $("#case_id").html();
        let doctor_responsible_on_scene = $("#event_doctor_responsible_on_scene_edit_input").val();
        $("#event_doctor_responsible_on_scene_edit_input").val("");
        if(doctor_responsible_on_scene == "") {
            $("#event_doctor_responsible_on_scene").html("Sem Informação");
            doctor_responsible_on_scene = null;
        }
        else {
            $("#event_doctor_responsible_on_scene").html(doctor_responsible_on_scene);
        }
        cancelUpdateEventDoctorResponsibleOnScene();
        axios.post("{{route('covid19.updateDoctorResponsibleOnScene')}}", {
            id: id,
            doctor_responsible_on_scene: doctor_responsible_on_scene,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventDoctorResponsibleOnDestination() {
        $("#event_doctor_responsible_on_destination_display").hide();
        $("#event_doctor_responsible_on_destination_edit").show();
    }

    function cancelUpdateEventDoctorResponsibleOnDestination() {
        $("#event_doctor_responsible_on_destination_edit").hide();
        $("#event_doctor_responsible_on_destination_display").show();
    }

    function submitUpdateEventDoctorResponsibleOnDestination() {
        let id = $("#case_id").html();
        let doctor_responsible_on_destination = $("#event_doctor_responsible_on_destination_edit_input").val();
        $("#event_doctor_responsible_on_destination_edit_input").val("");
        if(doctor_responsible_on_destination == "") {
            $("#event_doctor_responsible_on_destination").html("Sem Informação");
            doctor_responsible_on_destination = null;
        }
        else {
            $("#event_doctor_responsible_on_destination").html(doctor_responsible_on_destination);
        }
        cancelUpdateEventDoctorResponsibleOnDestination();
        axios.post("{{route('covid19.updateDoctorResponsibleOnDestination')}}", {
            id: id,
            doctor_responsible_on_destination: doctor_responsible_on_destination,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventOnSceneUnits() {
        $("#event_on_scene_units_display").hide();
        $("#event_on_scene_units_edit").show();
    }

    function cancelUpdateEventOnSceneUnits() {
        $("#event_on_scene_units_edit").hide();
        $("#event_on_scene_units_display").show();
    }

    function submitUpdateEventOnSceneUnits() {
        let id = $("#case_id").html();
        let on_scene_units = $("#event_on_scene_units_edit_input").val();
        if(on_scene_units == "") {
            $("#event_on_scene_units").html("Sem Informação");
            on_scene_units = null;
        }
        else if(on_scene_units == "1") {
            on_scene_units = "Sim - " + $("#event_on_scene_units_edit_input_specify").val();
            $("#event_on_scene_units").html(on_scene_units);
        }
        else {
            on_scene_units = "Não";
            $("#event_on_scene_units").html(on_scene_units);
        }
        $("#event_on_scene_units_edit_input").val("");
        $("#event_on_scene_units_edit_input_specify").val("");
        cancelUpdateEventOnSceneUnits();
        axios.post("{{route('covid19.updateOnSceneUnits')}}", {
            id: id,
            on_scene_units: on_scene_units,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateEventTotalDistance() {
        $("#event_total_distance_display").hide();
        $("#event_total_distance_edit").show();
    }

    function cancelUpdateEventTotalDistance() {
        $("#event_total_distance_edit").hide();
        $("#event_total_distance_display").show();
    }

    function submitUpdateEventTotalDistance() {
        let id = $("#case_id").html();
        let total_distance = $("#event_total_distance_edit_input").val();
        $("#event_total_distance_edit_input").val("");
        if(total_distance == "") {
            $("#event_total_distance").html("Sem Informação");
            total_distance = null;
        }
        else {
            $("#event_total_distance").html(total_distance);
        }
        cancelUpdateEventTotalDistance();
        axios.post("{{route('covid19.updateTotalDistance')}}", {
            id: id,
            total_distance: total_distance,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function activateAmbulance() {
        let id = $("#case_id").html();
        let vehicle = $("#occorrence_ambulance_create_amb").val();
        if(vehicle == "SIEM-PEM" || vehicle == "SIEM-RES") {
            let vehicle_type = (vehicle == "SIEM-PEM"? 2:3);
            let structure = $("#occorrence_ambulance_create_amb_non_covid19_structure").val();
            let vehicle_identification = $("#occorrence_ambulance_create_amb_non_covid19_vehicle_identification").val();
            axios.post("{{route('covid19.insertSIEMAmbulance')}}", {
                id: id,
                vehicle_type: vehicle_type,
                structure: structure,
                vehicle_identification: vehicle_identification
            })
            .then(function (response) {
                
            })
            .catch(function (error) {
                alert(error);
            });
        }
        else {
            axios.post("{{route('covid19.insertAmbulance')}}", {
                id: id,
                ambulance_id: vehicle,
            })
            .then(function (response) {
                closeCase();
                openCase(id);
            })
            .catch(function (error) {
                alert(error);
            });
        }
    }

    function reactivateAmbulance() {
        $("#occorrence_ambulance_create").show();
    }

    function updateSALOPActivationStatus() {
        $("#status_SALOP_activation_display").hide();
        $("#status_SALOP_activation_edit").show();
    }

    function cancelUpdateSALOPActivationStatus() {
        $("#status_SALOP_activation_edit").hide();
        $("#status_SALOP_activation_display").show();
    }

    function submitUpdateSALOPActivationStatus() {
        let id = $("#case_id").html();
        let status_SALOP_activation = $("#status_SALOP_activation_edit_input").val();
        $("#status_SALOP_activation_edit_input").val("");
        if(status_SALOP_activation == "") {
            $("#status_SALOP_activation").html("Sem Informação");
            status_SALOP_activation = null;
        }
        else {
            $("#status_SALOP_activation").html(status_SALOP_activation);
        }
        cancelUpdateSALOPActivationStatus();
        axios.post("{{route('covid19.updateSALOPActivationStatus')}}", {
            id: id,
            status_SALOP_activation: status_SALOP_activation,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateAMBActivationStatus() {
        $("#status_AMB_activation_display").hide();
        $("#status_AMB_activation_edit").show();
    }

    function cancelUpdateAMBActivationStatus() {
        $("#status_AMB_activation_edit").hide();
        $("#status_AMB_activation_display").show();
    }

    function submitUpdateAMBActivationStatus() {
        let id = $("#case_id").html();
        let status_AMB_activation = $("#status_AMB_activation_edit_input").val();
        $("#status_AMB_activation_edit_input").val("");
        if(status_AMB_activation == "") {
            $("#status_AMB_activation").html("Sem Informação");
            status_AMB_activation = null;
        }
        else {
            $("#status_AMB_activation").html(status_AMB_activation);
        }
        cancelUpdateAMBActivationStatus();
        axios.post("{{route('covid19.updateAMBActivationStatus')}}", {
            id: id,
            status_AMB_activation: status_AMB_activation,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateBaseExitStatus() {
        $("#status_base_exit_display").hide();
        $("#status_base_exit_edit").show();
    }

    function cancelUpdateBaseExitStatus() {
        $("#status_base_exit_edit").hide();
        $("#status_base_exit_display").show();
    }

    function submitUpdateBaseExitStatus() {
        let id = $("#case_id").html();
        let status_base_exit = $("#status_base_exit_edit_input").val();
        $("#status_base_exit_edit_input").val("");
        if(status_base_exit == "") {
            $("#status_base_exit").html("Sem Informação");
            status_base_exit = null;
        }
        else {
            $("#status_base_exit").html(status_base_exit);
        }
        cancelUpdateBaseExitStatus();
        axios.post("{{route('covid19.updateBaseExitStatus')}}", {
            id: id,
            status_base_exit: status_base_exit,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateArrivalOnSceneStatus() {
        $("#status_arrival_on_scene_display").hide();
        $("#status_arrival_on_scene_edit").show();
    }

    function cancelUpdateArrivalOnSceneStatus() {
        $("#status_arrival_on_scene_edit").hide();
        $("#status_arrival_on_scene_display").show();
    }

    function submitUpdateArrivalOnSceneStatus() {
        let id = $("#case_id").html();
        let status_arrival_on_scene = $("#status_arrival_on_scene_edit_input").val();
        $("#status_arrival_on_scene_edit_input").val("");
        if(status_arrival_on_scene == "") {
            $("#status_arrival_on_scene").html("Sem Informação");
            status_arrival_on_scene = null;
        }
        else {
            $("#status_arrival_on_scene").html(status_arrival_on_scene);
        }
        cancelUpdateArrivalOnSceneStatus();
        axios.post("{{route('covid19.updateArrivalOnSceneStatus')}}", {
            id: id,
            status_arrival_on_scene: status_arrival_on_scene,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateDepartureFromSceneStatus() {
        $("#status_departure_from_scene_display").hide();
        $("#status_departure_from_scene_edit").show();
    }

    function cancelUpdateDepartureFromSceneStatus() {
        $("#status_departure_from_scene_edit").hide();
        $("#status_departure_from_scene_display").show();
    }

    function submitUpdateDepartureFromSceneStatus() {
        let id = $("#case_id").html();
        let status_departure_from_scene = $("#status_departure_from_scene_edit_input").val();
        $("#status_departure_from_scene_edit_input").val("");
        if(status_departure_from_scene == "") {
            $("#status_departure_from_scene").html("Sem Informação");
            status_departure_from_scene = null;
        }
        else {
            $("#status_departure_from_scene").html(status_departure_from_scene);
        }
        cancelUpdateDepartureFromSceneStatus();
        axios.post("{{route('covid19.updateDepartureFromSceneStatus')}}", {
            id: id,
            status_departure_from_scene: status_departure_from_scene,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }


    function updateArrivalOnDestinationStatus() {
        $("#status_arrival_on_destination_display").hide();
        $("#status_arrival_on_destination_edit").show();
    }

    function cancelUpdateArrivalOnDestinationStatus() {
        $("#status_arrival_on_destination_edit").hide();
        $("#status_arrival_on_destination_display").show();
    }

    function submitUpdateArrivalOnDestinationStatus() {
        let id = $("#case_id").html();
        let status_arrival_on_destination = $("#status_arrival_on_destination_edit_input").val();
        $("#status_arrival_on_destination_edit_input").val("");
        if(status_arrival_on_destination == "") {
            $("#status_arrival_on_destination").html("Sem Informação");
            status_arrival_on_destination = null;
        }
        else {
            $("#status_arrival_on_destination").html(status_arrival_on_destination);
        }
        cancelUpdateArrivalOnDestinationStatus();
        axios.post("{{route('covid19.updateArrivalOnDestinationStatus')}}", {
            id: id,
            status_arrival_on_destination: status_arrival_on_destination,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateDepartureFromDestinationStatus() {
        $("#status_departure_from_destination_display").hide();
        $("#status_departure_from_destination_edit").show();
    }

    function cancelUpdateDepartureFromDestinationStatus() {
        $("#status_departure_from_destination_edit").hide();
        $("#status_departure_from_destination_display").show();
    }

    function submitUpdateDepartureFromDestinationStatus() {
        let id = $("#case_id").html();
        let status_departure_from_destination = $("#status_departure_from_destination_edit_input").val();
        $("#status_departure_from_destination_edit_input").val("");
        if(status_departure_from_destination == "") {
            $("#status_departure_from_destination").html("Sem Informação");
            status_departure_from_destination = null;
        }
        else {
            $("#status_departure_from_destination").html(status_departure_from_destination);
        }
        cancelUpdateDepartureFromDestinationStatus();
        axios.post("{{route('covid19.updateDepartureFromDestinationStatus')}}", {
            id: id,
            status_departure_from_destination: status_departure_from_destination,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateBaseReturnStatus() {
        $("#status_base_return_display").hide();
        $("#status_base_return_edit").show();
    }

    function cancelUpdateBaseReturnStatus() {
        $("#status_base_return_edit").hide();
        $("#status_base_return_display").show();
    }

    function submitUpdateBaseReturnStatus() {
        let id = $("#case_id").html();
        let status_base_return = $("#status_base_return_edit_input").val();
        $("#status_base_return_edit_input").val("");
        if(status_base_return == "") {
            $("#status_base_return").html("Sem Informação");
            status_base_return = null;
        }
        else {
            $("#status_base_return").html(status_base_return);
        }
        cancelUpdateBaseReturnStatus();
        axios.post("{{route('covid19.updateBaseReturnStatus')}}", {
            id: id,
            status_base_return: status_base_return,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateAvailableStatus() {
        $("#status_available_display").hide();
        $("#status_available_edit").show();
    }

    function cancelUpdateAvailableStatus() {
        $("#status_available_edit").hide();
        $("#status_available_display").show();
    }

    function submitUpdateAvailableStatus() {
        let id = $("#case_id").html();
        let status_available = $("#status_available_edit_input").val();
        if(status_available == "") {
            $("#status_available").html("Sem Informação");
            status_available = null;
        }
        else {
            $("#status_available").html(status_available);
        }
        cancelUpdateAvailableStatus();
        axios.post("{{route('covid19.updateAvailableStatus')}}", {
            id: id,
            status_available: status_available,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceINOP() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceINOP')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceAvailable() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceAvailable')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceOnBase() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceOnBase')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceBaseExit() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceBaseExit')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceArrivalOnScene() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceArrivalOnScene')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceDepartureFromScene() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceDepartureFromScene')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceArrivalOnDestination() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceArrivalOnDestination')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceDepartureFromDestination() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceDepartureFromDestination')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function ambulanceBaseReturn() {
        let id = $("#ambulance_id").html();
        axios.post("{{route('covid19.ambulanceBaseReturn')}}", {
            id: id,
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateOpenAmbulanceStructure() {
        $("#open_ambulance_structure_display").hide();
        $("#open_ambulance_structure_edit").show();
    }

    function cancelUpdateOpenAmbulanceStructure() {
        $("#open_ambulance_structure_edit").hide();
        $("#open_ambulance_structure_display").show();
    }

    function submitUpdateOpenAmbulanceStructure() {
        let id = $("#ambulance_id").html();
        let structure = $("#open_ambulance_structure_edit_input").val();
        $("#open_ambulance_structure_edit_input").val("");
        if(structure == "") {
            $("#open_ambulance_structure").html("Sem Informação");
            structure = null;
        }
        else {
            $("#open_ambulance_structure").html(structure);
        }
        cancelUpdateOpenAmbulanceStructure();
        axios.post("{{route('covid19.updateAmbulanceStructure')}}", {
            id: id,
            structure: structure,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateOpenAmbulanceRegion() {
        $("#open_ambulance_region_display").hide();
        $("#open_ambulance_region_edit").show();
    }

    function cancelUpdateOpenAmbulanceRegion() {
        $("#open_ambulance_region_edit").hide();
        $("#open_ambulance_region_display").show();
    }

    function submitUpdateOpenAmbulanceRegion() {
        let id = $("#ambulance_id").html();
        let region = $("#open_ambulance_region_edit_input").val();
        $("#open_ambulance_region_edit_input").val("");
        if(region == "") {
            $("#open_ambulance_region").html("Sem Informação");
            region = null;
        }
        else {
            $("#open_ambulance_region").html(region);
        }
        cancelUpdateOpenAmbulanceRegion();
        axios.post("{{route('covid19.updateAmbulanceRegion')}}", {
            id: id,
            region: region,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateOpenAmbulanceVehicleIdentification() {
        $("#open_ambulance_vehicle_identification_display").hide();
        $("#open_ambulance_vehicle_identification_edit").show();
    }

    function cancelUpdateOpenAmbulanceVehicleIdentification() {
        $("#open_ambulance_vehicle_identification_edit").hide();
        $("#open_ambulance_vehicle_identification_display").show();
    }

    function submitUpdateOpenAmbulanceVehicleIdentification() {
        let id = $("#ambulance_id").html();
        let vehicle_identification = $("#open_ambulance_vehicle_identification_edit_input").val();
        $("#open_ambulance_vehicle_identification_edit_input").val("");
        if(vehicle_identification == "") {
            $("#open_ambulance_vehicle_identification").html("Sem Informação");
            vehicle_identification = null;
        }
        else {
            $("#open_ambulance_vehicle_identification").html(vehicle_identification);
        }
        cancelUpdateOpenAmbulanceVehicleIdentification();
        axios.post("{{route('covid19.updateAmbulanceVehicleIdentification')}}", {
            id: id,
            vehicle_identification: vehicle_identification,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateOpenAmbulanceActivePrevention() {
        $("#open_ambulance_active_prevention_display").hide();
        $("#open_ambulance_active_prevention_edit").show();
    }

    function cancelUpdateOpenAmbulanceActivePrevention() {
        $("#open_ambulance_active_prevention_edit").hide();
        $("#open_ambulance_active_prevention_display").show();
    }

    function submitUpdateOpenAmbulanceActivePrevention() {
        let id = $("#ambulance_id").html();
        let active_prevention = $("#open_ambulance_active_prevention_edit_input").val();
        $("#open_ambulance_active_prevention_edit_input").val(1);
        if(active_prevention == "") {
            $("#open_ambulance_active_prevention").html("Sem Informação");
            active_prevention = null;
        }
        else {
            if(active_prevention == "1") {
                $("#open_ambulance_active_prevention").html("Prevenção Ativa");
            }
            else {
                $("#open_ambulance_active_prevention").html("Prevenção Passiva");
            }
        }
        cancelUpdateOpenAmbulanceActivePrevention();
        axios.post("{{route('covid19.updateAmbulanceActivePrevention')}}", {
            id: id,
            active_prevention: active_prevention,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateCaseNotes() {
        let id = $("#case_id").html();
        let notes = $("#case_notes_textarea").val();
        if(notes == "") {
            notes = null;
        }
        axios.post("{{route('covid19.updateCaseNotes')}}", {
            id: id,
            notes: notes,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function addObservation() {
        let id = $("#case_id").html();
        let observation = $("#case_observations_textarea").val();
        if(observation != "") {
            $("#case_observations_textarea").val("");
            axios.post("{{route('covid19.addObservation')}}", {
                id: id,
                observation: observation,
            })
            .then(function (response) {
            })
            .catch(function (error) {
                alert(error);
            });
        }
    }

    function removeObservation(id) {
        let case_id = $("#case_id").html();
        axios.post("{{route('covid19.removeObservation')}}", {
            id: case_id,
            observation_id: id
        })
        .then(function (response) {
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function insertTeamMember() {
        let id = $("#case_id").html();
        let name = $("#case_team_name").val();
        let age = $("#case_team_age").val();
        let contact = $("#case_team_contact").val();
        let type = $("#case_team_type").val();
        $("#case_team_name").val("");
        $("#case_team_age").val("");
        $("#case_team_contact").val("");
        $("#case_team_type").val("Condutor");
        axios.post("{{route('covid19.insertTeamMember')}}", {
            id: id,
            name: name,
            age: age,
            contact: contact,
            type: type
        })
        .then(function (response) {

        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateTeamMemberName(id) {
        $("#team_member_name_display"+id).hide();
        $("#team_member_name_edit"+id).show();
    }

    function cancelUpdateTeamMemberName(id) {
        $("#team_member_name_edit"+id).hide();
        $("#team_member_name_display"+id).show();
    }

    function submitUpdateTeamMemberName(id) {
        let case_id = $("#case_id").html();
        let name = $("#team_member_name_edit_input"+id).val();
        $("#team_member_name_edit_input"+id).val("");
        cancelUpdateTeamMemberName(id);
        axios.post("{{route('covid19.updateTeamMemberName')}}", {
            id: case_id,
            team_member_id: id,
            name: name,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateTeamMemberAge(id) {
        $("#team_member_age_display"+id).hide();
        $("#team_member_age_edit"+id).show();
    }

    function cancelUpdateTeamMemberAge(id) {
        $("#team_member_age_edit"+id).hide();
        $("#team_member_age_display"+id).show();
    }

    function submitUpdateTeamMemberAge(id) {
        let case_id = $("#case_id").html();
        let age = $("#team_member_age_edit_input"+id).val();
        $("#team_member_age_edit_input"+id).val("");
        cancelUpdateTeamMemberAge(id);
        axios.post("{{route('covid19.updateTeamMemberAge')}}", {
            id: case_id,
            team_member_id: id,
            age: age,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateTeamMemberContact(id) {
        $("#team_member_contact_display"+id).hide();
        $("#team_member_contact_edit"+id).show();
    }

    function cancelUpdateTeamMemberContact(id) {
        $("#team_member_contact_edit"+id).hide();
        $("#team_member_contact_display"+id).show();
    }

    function submitUpdateTeamMemberContact(id) {
        let case_id = $("#case_id").html();
        let contact = $("#team_member_contact_edit_input"+id).val();
        $("#team_member_contact_edit_input"+id).val("");
        cancelUpdateTeamMemberContact(id);
        axios.post("{{route('covid19.updateTeamMemberContact')}}", {
            id: case_id,
            team_member_id: id,
            contact: contact,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function updateTeamMemberType(id) {
        $("#team_member_type_display"+id).hide();
        $("#team_member_type_edit"+id).show();
    }

    function cancelUpdateTeamMemberType(id) {
        $("#team_member_type_edit"+id).hide();
        $("#team_member_type_display"+id).show();
    }

    function submitUpdateTeamMemberType(id) {
        let case_id = $("#case_id").html();
        let type = $("#team_member_type_edit_input"+id).val();
        $("#team_member_type_edit_input"+id).val("Condutor");
        cancelUpdateTeamMemberType(id);
        axios.post("{{route('covid19.updateTeamMemberType')}}", {
            id: case_id,
            team_member_id: id,
            type: type,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function removeTeamMember(team_member_id) {
        let case_id = $("#case_id").html();
        axios.post("{{route('covid19.removeTeamMember')}}", {
            id: case_id,
            team_member_id: team_member_id,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function addContact() {
        let id = $("#ambulance_id").html();
        let contact = $("#ambulance_contacts_contact").val();
        let name = $("#ambulance_contacts_name").val();
        let sms = $("#ambulance_contacts_sms").is(":checked");
        $("#ambulance_contacts_contact").val("");
        $("#ambulance_contacts_name").val("");
        $("#ambulance_contacts_sms").prop( "checked", false );
        axios.post("{{route('covid19.addContact')}}", {
            id: id,
            contact: contact,
            name: name,
            sms: sms
        })
        .then(function (response) {
            closeAmbulance();
            openAmbulance(id);
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function removeContact(contact_id) {
        let ambulance_id = $("#ambulance_id").html();
        axios.post("{{route('covid19.removeContact')}}", {
            id: ambulance_id,
            contact_id: contact_id,
        })
        .then(function (response) {
            
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function cancelCase() {
        let id = $("#case_id").html();
        axios.post("{{route('covid19.cancelCase')}}", {
            id: id
        })
        .then(function (response) {
            closeCase();
        })
        .catch(function (error) {
            alert(error);
        });
    }

    function SwapPanel() {
        let current = $("#SwapPanel").data("current");
        if(current == "panel") {
            $("#panel").hide();
            $("#cases_history").show();
            $("#SwapPanel").data("current","cases_history");
            $("#SwapPanel").html("Painel de Operador");
            cases_history = true;
            $("#nova-occorencia-button").hide();
            $("#nova-ambulancia-button").hide();
        }
        else {
            $("#cases_history").hide();
            $("#panel").show();
            $("#SwapPanel").data("current","panel");
            $("#SwapPanel").html("Histórico de Casos");
            cases_history = false;
            $("#nova-occorencia-button").show();
            $("#nova-ambulancia-button").show();
        }
    }

    Echo.channel('COVID19UpdateCase').listen('COVID19UpdateCase', (data) => {
        if(($("#case").data('bs.modal') || {})._isShown) {
            let case_id = $("#case_id").html();
            if(case_id == data.case.id) {
                closeCase();
                openCase(data.case.id);
            }
        }
        if(data.case.status_AMB_activation != null) {
            if(isOpenCaseCreated(data.case)) {
                removeOpenCase(data.case);
            }
        }
        else {
            if(!isOpenCaseCreated(data.case)) {
                createOpenCase(data.case);
            }
        }
    });

    Echo.channel('COVID19UpdateAmbulance').listen('COVID19UpdateAmbulance', (data) => {
        if(($("#ambulance").data('bs.modal') || {})._isShown) {
            let ambulance_id = $("#ambulance_id").html();
            if(ambulance_id == data.ambulance.id) {
                closeAmbulance();
                openAmbulance(data.ambulance.id);
            }
        }
        if(!isAmbulanceCreated(data.ambulance)) {            
            createAmbulance(ambulance);
        }
        else {
            updateAmbulance(data.ambulance);
        }
    });

    Echo.channel('COVID19DeleteCase').listen('COVID19DeleteCase', (data) => {
        if(($("#case").data('bs.modal') || {})._isShown) {
            let case_id = $("#case_id").html();
            if(case_id == data.case.id) {
                closeCase();
            }
        }
        if(isOpenCaseCreated(data.case)) {
            removeOpenCase(data.case);
        }
    });
</script>
@endsection