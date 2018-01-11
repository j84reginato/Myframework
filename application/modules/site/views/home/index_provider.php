
        <!-- Modal para seleção do tipo de usuário -->
        <?php if (!isset($user_type) || $user_type == null) { ?>
            <div class="modal fade" id="modal-select-user" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title text-center" id="gridSystemModalLabel">Seja bem-vindo</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <form name="user_type_form" action="index.php" method="post">
                                    <div class="col-md-6 text-center">
                                        <img class="user-type" src="<?php echo base_url('assets/img/patient.jpg');?>">
                                        <button type="submit" name='user_type' class="btn btn-default text-center" value="patient">&nbsp;Sou paciente&nbsp;</button>
                                    </div>
                                    <div class="col-md-6 col-md-offset-0 text-center">
                                        <img class="user-type" src="<?php echo base_url('assets/img/doctor.jpg');?>">
                                        <button type="submit" name="user_type" class="btn btn-primary text-center" value="provider">Sou prestador</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer text-center">
                            <div class="modal-observation text-center">Clique no link para acessar nossos <a href="#">termos de uso</a></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Menu de navegação -->
        <section class="navs">
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="logo" href="#"><img alt="Acesso Médico" src="<?php echo base_url('assets/img/stethoscope.ico');?>" height="63px">
                            <span>Acesso Médico</span>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="#what">O que é?</a></li>
                            <li><a href="#why">Por que devo usar?</a></li>
                            <li><a href="#how">Como posso tornar-me um parceiro?</a></li>
                            <li><a href="login.php">Entrar / Cadastrar</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </section>

        <!-- Banners do tipo slider -->
        <section class="heading-slider">
            <div id="starting-slider" class="owl-carousel owl-theme">
                <div class="item">
                    <div class="slider-1">
                        <div class="slider-inner">
                            <div class="container">
                                <div class="row">
                                    <div class="slider-inner-text">
                                        <!--<h1>Seja nosso parceiro</h1>-->
                                        <!--<p>Podemos lhe ajudar a encontrar milhares de novos Pacientes</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="slider-2">
                        <div class="slider-inner">
                            <div class="container">
                                <div class="row">
                                    <div class="slider-inner-text">
                                        <!--<h1>Reduza os custos e aumente as consultas.</h1>-->
                                        <!--<p>Reduza drasticamente os custos com telefonia e disponibilize a sua agenda 24h/7 aumentando a possibilidade de receber consultas mesmo nos horários e dias em que estiver fechado.</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="slider-3">
                        <div class="slider-inner">
                            <div class="container">
                                <div class="row">
                                    <div class="slider-inner-text">
                                        <!--<h1>Otimize o tempo da sua Clínica!</h1>-->
                                        <!--<p>Ganhe mais tempo nos processos administrativos. Seja qual for sua especialidade o Aplicativo Cartão Médico simplifica as tarefas diárias com o gerenciamento das consultas.</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Painel de pesquisa (Exibido na inicialização ou quando usuário for paciente) -->
        <?php if (!isset($user_type) || $user_type == 'patient') { ?>
            <div class="search-panel">
                <div class="col-lg-6 col-lg-offset-1 col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <?php // if ($error_message != '') { ?>
                        <!--<div class="alert alert-danger" role="alert" style="border-radius: 8px; padding: 15px; opacity: .8;">-->
                            <?php // echo $error_message; ?>
                        <!--</div>-->
                    <?php // } ?>
                    <div class="well" id="search-form">
                        <form class="form-horizontal" name="search" method="GET" action="index.php">
                            <input type="hidden" name="action" class="form-control" value="search">
                            <input type="hidden" name="type" class="form-control" id="selected-type" value="med_spcts">
                            <div class="form-group">
                                <ul class="search-opcao">
                                    <li><a class="tipo-pesquisa btn btn-white active" href="javascript:void(0);" id="med_spcts" title="Agendar Consulta">Consulta</a></li>
                                    <li><a class="tipo-pesquisa btn btn-white" href="javascript:void(0);" id="lab_exams" title="Exame Laboratorial">Exame Laboratorial</a></li>
                                    <li><a class="tipo-pesquisa btn btn-white" href="javascript:void(0);" id="img_exams" title="Exame de Imagem">Exame de Imagem</a></li>
                                </ul>
                            </div>
                            <div class="form-group" id="options">
                                <div>
                                    <select name="category" class="chosen-select search-esp service-type" id="option_med_spcts" autofocus>
                                        <?php if (isset($med_spcts) && count($med_spcts) > 0) {
                                            foreach ($med_spcts as $key => $value) { ?>
                                                <option value=<?php echo $key ?>><?php echo $value ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div>
                                    <select name="category" class="chosen-select search-esp service-type hidden" disabled="disabled" id="option_lab_exams">
                                        <?php if (isset($lab_exams) && count($lab_exams) > 0) {
                                            foreach ($lab_exams as $key => $value) { ?>
                                                <option value=<?php echo $key ?>><?php echo $value ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div>
                                    <select name="category" class="chosen-select search-esp service-type hidden" disabled="disabled" id="option_img_exams">
                                        <?php if (isset($img_exams) && count($img_exams) > 0) {
                                            foreach ($img_exams as $key => $value) { ?>
                                                <option value=<?php echo $key ?>><?php echo $value ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <select name="city" class="chosen-select search-esp" id="option_local">
                                    <?php if (isset($cities) && count($cities) > 0) {
                                        foreach ($cities as $key => $value) { ?>
                                            <option value=<?php echo $key ?>><?php echo $value ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="go" id="btn-search" class="btn btn-info btn-round" value="Buscar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Painel formulário login (Exibido se chamado o controlador login) -->
        <?php if ($login) { ?>
            <div class="search-panel">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 well" id="search-form">
                                <?php if ($this->root_ref['error_list'] != ('')) { ?>
                                    <div class="alert alert-danger">
                                        <?php echo (isset($this->root_ref['error_list'])) ? $this->root_ref['error_list'] : ''; ?>
                                    </div>
                                <?php } ?>
                                <div class="col-md-5">
                                    <h2>Digite seus dados de acesso</h2>
                                    <form name="user_login" class="form-horizontal" action="<?php echo (isset($this->root_ref['siteUrl'])) ? $this->root_ref['siteUrl'] : ''; ?>login.php" method="post">
                                        <div class="form-group col-md-10">
                                            <input type="hidden" name="action" class="form-control" value="login">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                                                <input type="text" name="username" class="form-control" value="<?php echo (isset($this->root_ref['user'])) ? $this->root_ref['user'] : ''; ?>" placeholder="Insira seu nome de usuário" autofocus>
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
                                                <input type="password" name="password" class="form-control" value="" placeholder="Insira sua senha">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-10">
                                            <input type="submit" name="input" class="btn btn-info btn-round" id="btn-search" value="Login">
                                            <div class="pull-right">
                                                <input type="checkbox" name="remember_me" value="1"> Lembre-me
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-10">
                                            <a href="#">Esqueceu sua senha?</a>
                                        </div>
                                        <div class="form-group col-md-10 visible-xs">
                                            <a href="register_patient_user.php">Não possui cadastro?</a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                                <div class="col-md-2 hidden-xs">
                                    <h2 class="or"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span></h2>
                                </div>
                                <div class="col-md-5 hidden-xs">
                                    <h2>Não possui cadastro?</h2>
                                    <h3><a href="register_patient_user.php">Registre-se agora</a>
                                    <!--<h3><a href="register_provider_user.php">Registre-se agora</a>-->                                        
                                    <br>
                                    Leva apenas um minuto</h3>
                                    <p>Após se cadastrar você poderá:</p>
                                    <ul>
                                        <?php if ($user_type == 'patient') { ?>
                                            <li>- Agendar consultas com os mais conceituados profissionais de nossa rede</li>
                                            <li>- Agendar exames laboratoriais e de diagnostico por imagem</li>
                                            <li>- Tudo isso de qualquer lugar e a qualquer momento</li>
                                            <li><b>Não perca mais tempo! Experimente agora mesmo: é GRÁTIS!</b></li>
                                        <?php } elseif ($user_type == 'provider') { ?>                                            
                                            <li>- Ter a chance de incrementar seu faturamento;</li>
                                            <li>- Obter uma redução de até 100% de faltas à consultas e/ou exames;</li>
                                            <li>- Permitir que seus pacientes agendem consultas e/ou exames fora do horário comercial.</li>
                                            <li><b>Não perca mais tempo! Experimente agora mesmo!</b></li>                                            
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- O que é? (Painel azul) -->
        <section class="starting-text" id="what">
            <div class="container">
                <div class="row">
                    <div class="welcome">
                        <!--Paciente-->
                        <h2 class="welcome-title">Agende sua consulta de forma rápida e com um custo super acessível</h2>
                        <p class="welcome-txt">Experimente agora mesmo.<br>O <b>Cartão Médico</b> é totalmente gratuíto!</p>
                        <!--Prestador-->
                        <!--<h2 class="welcome-title">O que é?</h2>-->
                        <!--<p class="welcome-txt">O <b>Cartão Médico</b> é um aplicativo onde os profissionais de saúde cadastram suas horas disponíveis ou ociosas com um valor acessível para milhares de usuários que não possuem plano de saúde.</p>-->
                        <!--<button class="welcome-btn">Saiba Mais</button>-->
                    </div>
                </div>
            </div>
        </section>

        <!-- Por que? -->
        <section id= "why">
            <div class= "container">
                <div class= "row">
                    <div class= "col-lg-12 col-sm-12 col-xs-12">
                        <h2 class= "headline text-center">Por que escolher Acesso Médico?</h2>
                        <p class="sub-headline text-center">Alguns motivos:</p>
                    </div>
                </div>
                <div class= "row">
                    <div class= "col-lg-4 col-sm-6 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <!--Paciente-->
                                <img class= "img-responsive center-block" src="<?php echo base_url('assets/img/reason1.jpg'); ?>" alt="Profissionais da saúde">
                                <!--Prestador-->
                                <!--<img class= "img-responsive center-block" src="themes/modern/img/reason2.jpg" alt="Aumento do faturamento">-->
                            </div>
                            <div class= "service-description text-center">
                                <h4 class= "service-heading">Profissionais qualificados</h4>
                                <p>Não existe maior patrimônio para uma empresa do que contar com pessoas de <b>alto nível profissional</b>, pois elas fazem a diferença em qualquer organização. Nossa equipe de especialistas é fruto de um <b>rigoroso processo seletivo e supervisão</b> e é formada por profissionais que amam atuar em suas funções.</p>
                                <!--Prestador-->
                                <!--<h4 class= "service-heading">Aumento do faturamento</h4>-->
                                <!--<p>Crescimento no volume de consultas particulares e/ou exames com pagamento no dia.</p>-->
                                <!--<a href="#" class="rm-btn btn btn-primary">Saiba mais <i class="fa fa-caret-right"></i></a>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class= "col-lg-4 col-sm-6 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <img class= "img-responsive center-block" src="<?php echo base_url('assets/img/reason2.jpg'); ?>" alt="Preços acessíveis">
                                <!--<img class= "img-responsive center-block" src="themes/modern/img/reason4.jpg" alt="Zero Absenteísmo">-->
                            </div>
                            <div class= "service-description text-center">
                                <h4 class= "service-heading">Preços acessíveis</h4>
                                <p>O <b>Cartão Médico</b> oferece preços especiais e pagamentos facilitados em todos os seus serviços, que podem ser divididos em até 12x no cartão de crédito. Tudo isso sem esquecer da <b>qualidade</b> que você merece. Você só paga pelo serviço que utilizar, <b>sem mensalidade!</b><br>Experimente e comprove!</p>
                                <!--<h4 class= "service-heading">Zero Absenteísmo</h4>-->
                                <!--<p>Redução de até 100% de faltas à consultas e/ou exames.</p>-->
                                <!--<a href="#" class="rm-btn btn btn-primary">Saiba mais <i class="fa fa-caret-right"></i></a>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class= "col-lg-4 col-sm-6 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <img class= "img-responsive center-block" src="<?php echo base_url('assets/img/reason3.jpg'); ?>" alt="Facilidade de uso">
                                <!--<img class= "img-responsive center-block" src="themes/modern/img/reason5.jpg" alt="Agenda 24h">-->
                            </div>
                            <div class= "service-description text-center">
                                <h4 class= "service-heading">Facilidade</h4>
                                <p>O <b>Cartão Médico</b> é o aplicativo que permite agendar consultas médicas e exames de onde você estiver! Não perca mais tempo em pronto-atendimentos ou ao telefone. Basta entrar no aplicativo, escolher a especialidade e fazer o agendamento. Simples assim, <b>sem filas, sem burocracia, sem esperas</b>.</p>
                                <!--<h4 class= "service-heading">Agenda 24h</h4>-->
                                <!--<p>Permita que seus pacientes agendem consultas e/ou exames fora do horário comercial.</p>-->
                                <!--<a href="#" class="rm-btn btn btn-primary">Saiba mais <i class="fa fa-caret-right"></i></a>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--Os 3 abaixo serão usados somente se prestador-->
                    <div class= "col-lg-4 col-sm-6 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <img class= "img-responsive center-block" src="<?php echo base_url('assets/img/reason6.jpg'); ?>" alt="Indicação">
                            </div>
                            <div class= "service-description text-center">
                                <h4 class= "service-heading">Indicação</h4>
                                <p>Receba o devido reconhecimento e indicação dos seus pacientes!</p>
                                <!--<a href="#" class="rm-btn btn btn-primary">Saiba mais <i class="fa fa-caret-right"></i></a>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class= "col-lg-4 col-sm-6 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <img class= "img-responsive center-block" src="<?php echo base_url('assets/img/reason7.jpg'); ?>" alt="Lembretes SMS/E-mail">
                            </div>
                            <div class= "service-description text-center">
                                <h4 class= "service-heading">Lembretes SMS/E-mail</h4>
                                <p>Para não esquecer o agendamento, lembretes são enviados 24h antes!</p>
                                <!--<a href="#" class="rm-btn btn btn-primary">Saiba mais <i class="fa fa-caret-right"></i></a>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class= "col-lg-4 col-sm-6 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <img class= "img-responsive center-block" src="<?php echo base_url('assets/img/reason8.jpg'); ?>" alt="Acesso à Internet">
                            </div>
                            <div class= "service-description text-center">
                                <h4 class= "service-heading">Acesso à Internet</h4>
                                <p>60% dos brasileiros já tem acesso a internet. Esteja onde estão os pacientes.</p>
                                <!--<a href="#" class="rm-btn btn btn-primary">Saiba mais <i class="fa fa-caret-right"></i></a>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- clearfix -->
        <section id= "services">
            <div class= "container">
                <div class= "row">
                    <div class= "col-lg-12 col-sm-12 col-xs-12">
                        <p class="sub-headline text-center"></p>
                    </div>
                </div>
                <div class= "row text-center"></div>
            </div>
        </section>

        <!-- Seção 'Como?' (painel azul) -->
        <section class= "starting-text" id="how">
            <div class="container">
                <div class="row">
                    <div class="welcome">
                        <h2 class="welcome-title">Como funciona?</h2>
                        <p class="welcome-txt">Basta seguir 3 simples passos:</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Passos -->
        <section id= "services">
            <div class= "container">
                <div class= "row">
                    <div class= "col-lg-12 col-sm-12 col-xs-12"></div>
                </div>
                <div class="row">
                    <div class= "col-lg-4 col-sm-6 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <img class= "img-responsive center-block" src="" alt="">
                            </div>
                            <div class= "service-description text-center">
                                <h1 class= "service-heading">1</h1>
                                <i class="fa fa-user-md steps" aria-hidden="true"></i>
                                <i class="fa fa-flask steps" aria-hidden="true"></i>
                                <h2>Selecione</h2>
                                <p>Consulta ou exame</p>
                                <!--<i class="fa fa-laptop steps" aria-hidden="true"></i>-->
                                <!--<h2>Inscreva-se</h2>-->
                                <!--<p>Envie seus dados de cadastro pelo <a href="login.php" title="Nosso formulário">nosso formulário</a></p>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <img class= "img-responsive center-block" src="" alt="">
                            </div>
                            <div class= "service-description text-center">
                                <h1 class= "service-heading">2</h1>
                                <i class="fa fa-credit-card steps" aria-hidden="true"></i>
                                <h2>Pagamento</h2>
                                <p>Escolha a forma de pagamento</p>
                                <!--<i class="fa fa-hourglass-start steps" aria-hidden="true"></i>-->
                                <!--<h2>Aguarde</h2>-->
                                <!--<p>Nossa equipe analisará seus dados e entrará em contato o mais breve possível</p>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class= "hservice">
                            <div class= "service-img">
                                <img class= "img-responsive center-block" src="" alt="">
                            </div>
                            <div class= "service-description text-center">
                                <h1 class= "service-heading">3</h1>
                                <i class="fa fa-calendar-check-o steps" aria-hidden="true"></i>
                                <h2>Confirmação</h2>
                                <p>Aguarde a confirmação do agendamento</p>
                                <!--<i class="fa fa-handshake-o steps" aria-hidden="true"></i>-->
                                <!--<h2>Confirmação</h2>-->
                                <!--<p>Com os dados corretos, assinamos o contrato e você já fará parte do <strong>nosso time</strong></p>-->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Depoimentos -->
        <section id= "testimonial" class="text-center">
            <div class="testimonial-wrapper">
                <div class="container">
                    <div class="row client-content text-center">
                        <div class="col-md-8">
                            <div class="row">
                                <h1>Depoimentos</h1>
                            </div>
                            <div class="row">
                                <div class="sub-headline">
                                    <p>O que os pacientes dizem sobre nós</p>
                                </div>
                            </div>
                            <!-- Slides -->
                            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner" role="listbox">
                                    <div class="item active">
                                        <div id="client-speech">
                                            <div class="item">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <i class="fa fa-quote-left"></i>
                                                    </div>
                                                    <div class="col-md-8 col-md-offset-2">
                                                        <p class="client-comment text-center">
                                                            O aplicativo é fácil de usar. O atendimento foi ótimo, o médico muito prestativo e atencioso. Aprovo e recomendo!!!
                                                        </p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-6 col-xs-offset-3">
                                                        <img class="img-circle img-responsive center-block" src="<?php echo base_url('assets/img/client1.jpg'); ?>" alt="Image cliente">
                                                    </div>
                                                </div>
                                                <div class= "row text-center">
                                                    <p class="client-name text-center">Viviane Gritten</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div id="client-speech">
                                            <div class="item">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <i class="fa fa-quote-left"></i>
                                                    </div>
                                                    <div class="col-md-8 col-md-offset-2">
                                                        <p class="client-comment text-center">
                                                            Adorei! Tem bastante médicos, dentistas, exames e é bem facil de usar!
                                                        </p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-6 col-xs-offset-3">
                                                        <img class="img-circle img-responsive center-block" src="<?php echo base_url('assets/img/client2.jpg'); ?>" alt="Image cliente">
                                                    </div>
                                                </div>
                                                <div class= "row text-center">
                                                    <p class="client-name text-center">João de Deus</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div id="client-speech">
                                            <div class="item">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <i class="fa fa-quote-left"></i>
                                                    </div>
                                                    <div class="col-md-8 col-md-offset-2">
                                                        <p class="client-comment text-center">
                                                            Simplesmente perfeito! Recomendo.
                                                        </p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <i class="fa fa-quote-right"></i>
                                                    </div>
                                                    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-6 col-xs-offset-3">
                                                        <img class="img-circle img-responsive center-block" src="<?php echo base_url('assets/img/client3.jpg'); ?>" alt="Image cliente">
                                                    </div>
                                                </div>
                                                <div class= "row text-center">
                                                    <p class="client-name text-center">Alice Braga</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                    <i class="fa fa-angle-left fa-3x"></i>
                                    <span class="sr-only">Anterior</span>
                                </a>
                                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                    <i class="fa fa-angle-right fa-3x"></i>
                                    <span class="sr-only">Próximo</span>
                                </a>
                            </div>
                        </div>
                        <!-- Fale Conosco -->
                        <div class="col-md-4">
                            <div class= "appointment">
                                <div class="header text-center">
                                    <h2>Fale conosco</h2>
                                    <a href="#" class="number">
                                        <i class="fa fa-phone fa-fw"></i>
                                        (41) 99999-9999
                                    </a>
                                    <span class="or">OU</span>
                                </div>
                                <div class="row">
                                    <form method="post" action="#">
                                        <div class= "form">
                                            <div class="input-group margin-bottom-sm col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                                                <input class="form-control" type="text" placeholder="Nome Completo *" required>
                                            </div>
                                            <div class="input-group margin-bottom-sm col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                                                <input class="form-control" type="text" placeholder="Email *" required>
                                            </div>
                                            <div class="input-group margin-bottom-sm col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                                                <input class="form-control" type="text" placeholder="Telefone *" required>
                                            </div>
                                            <div class="input-group margin-bottom-sm col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                                                <input class="form-control" type="text" placeholder="Assunto *" required>
                                            </div>
                                        </div>
                                        <div class="input-group margin-bottom-sm col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                                            <textarea class="form-control" rows="6" placeholder="Mensagem *" required></textarea>
                                        </div>
                                        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                                            <div class="captcha-container">
                                                <label>Não sou um robo </label><br/>
                                                <img src="" alt="">
                                                <input type="text" class="captcha required" name="captcha" maxlength="5" title=" Please enter the code characters displayed in image!">
                                            </div>
                                            <input class="btn btn-primary send" type="submit" value="Enviar">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Rodapé -->
        <section id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <ul class="text-center contact">
                            <li class= "socials-icons">
                                <a href="#" data-toggle="tooltip" title="Share in Facebook" class="facebook"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li class= "socials-icons">
                                <a href="#" data-toggle="tooltip" title="Share in Twitter" class="twitter"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li class= "socials-icons">
                                <a href="#" data-toggle="tooltip" title="Share in Google +" class="google-plus"><i class="fa fa-google-plus"></i></a>
                            </li>
                            <li class= "socials-icons">
                                <a href="#" data-toggle="tooltip" title="Share in Instagram" class="instagram"><i class="fa fa-instagram"></i></a>
                            </li>
                            <li class= "socials-icons">
                                <a href="#" data-toggle="tooltip" title="Share in Pinterest" class="pinterest"><i class="fa fa-pinterest"></i></a>
                            </li>
                            <li class= "socials-icons">
                                <a href="#" data-toggle="tooltip" title="Connect with Skype" class="skype"><i class="fa fa-skype"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="copy-right-text text-center">
                            <!--&copy; Copyright 2017, Jonatan N. Reginato. Powered by <a href="http://www.jnreginato.com.br/">j84reginato</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script type="text/javascript" src="<?php echo base_url('assets/ext/jquery//jquery.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/ext/owl-corousel/owl.carousel.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/ext/isotope/isotope.pkgd.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/ext/wow//wow.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.js'); ?>"></script>

        <script>
            new WOW().init();
        </script>

        <script>
            $(document).ready(function () {
                $("#starting-slider").owlCarousel({
                    autoPlay: 8000,
                    navigation: false, // Show next and prev buttons
                    slideSpeed: 700,
                    paginationSpeed: 1000,
                    singleItem: true
                });
            });
        </script>

        <script>
            $(function() {
                // init Isotope
                var $container = $('.isotope').isotope
                ({
                    itemSelector: '.element-item',
                    layoutMode: 'fitRows'
                });


                // bind filter button click
                $('#filters').on('click', 'button', function ()
                {
                    var filterValue = $(this).attr('data-filter');
                    // use filterFn if matches value
                    $container.isotope({filter: filterValue});
                });

                // change is-checked class on buttons
                $('.button-group').each(function (i, buttonGroup)
                {
                    var $buttonGroup = $(buttonGroup);
                    $buttonGroup.on('click', 'button', function ()
                    {
                        $buttonGroup.find('.is-checked').removeClass('is-checked');
                        $(this).addClass('is-checked');
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $(".tipo-pesquisa").click(function() {
                    id = $(this).attr("id");
                    $("ul.search-opcao li a").removeClass("active");
                    $("ul.search-opcao li a#" + id).addClass("active");

                    $(".service-type").addClass("hidden");
                    $(".service-type").prop("disabled", true);

                    $("#option_" + id).removeClass("hidden");
                    $("#option_" + id).prop("disabled", false);

                    $("#selected-type").val(id);
                });
            });
        </script>

        <script>
            <?php if (!isset($this->root_ref['user_type']) || $this->root_ref['user_type'] == '') { ?>
                $(document).ready(
                    function() {
                        setTimeout(function(){$('#modal-select-user').modal({backdrop: 'static', keyboard: false});}, 1000);
                    }
                );
            <?php } ?>
        </script>
    </body>
</html>