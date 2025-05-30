-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: MariaDB:3306
-- Tempo de geração: 30/05/2025 às 10:25
-- Versão do servidor: 11.6.2-MariaDB-ubu2404
-- Versão do PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `revista_flow`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avisos`
--

CREATE TABLE `avisos` (
  `id` int(11) NOT NULL,
  `conteudo` varchar(800) NOT NULL,
  `data_aviso` datetime NOT NULL,
  `data_expira` date NOT NULL,
  `id_usuario_aviso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `avisos`
--

INSERT INTO `avisos` (`id`, `conteudo`, `data_aviso`, `data_expira`, `id_usuario_aviso`) VALUES
(12, 'Apresentação Revista', '2025-04-25 13:22:53', '2025-04-25', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `conteudo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `post_id`, `user_id`, `conteudo`) VALUES
(15, 78, 7, 'Parece que algo importante aconteceu. A situação está gerando muitos comentários nas redes sociais, e as reações são bem diversas. Vale a pena acompanhar os próximos capítulos dessa história.'),
(16, 82, 7, 'A notícia está bem informativa, vou agilizar meu processo de inscrição no enem :)'),
(17, 92, 4, 'QUE CALORRRRR'),
(18, 76, 1, 'Adorei! Muito inspirador. ✨'),
(19, 74, 1, 'Que conteúdo incrível! Muito bem explicado. 🙌'),
(20, 81, 1, 'Excelente postagem, me ajudou muito a entender esse tema. 👏'),
(21, 92, 1, 'Me fez refletir sobre esse tema de uma forma totalmente nova. Parabéns! 🌟'),
(22, 89, 1, 'Esse post merece mais reconhecimento! Super relevante e útil. 👍'),
(23, 75, 1, 'Gostei demais! Tô salvando para ler de novo mais tarde. 😍'),
(24, 82, 1, 'Uau, que visão! Muito aprendizado aqui, obrigado por compartilhar. 🤩'),
(25, 96, 2, 'Uau, que visão! Muito aprendizado aqui, obrigado por compartilhar. 🤩'),
(26, 75, 4, 'Realmente !!'),
(27, 76, 2, 'Esse post merece mais reconhecimento! Super relevante e útil. 👍'),
(28, 95, 4, 'Vou estudar com este conteúdo :D'),
(29, 95, 2, 'Esse post merece mais reconhecimento! Super relevante e útil. 👍'),
(30, 92, 2, 'Esse post merece mais reconhecimento! Super relevante e útil. 👍'),
(31, 74, 4, 'Que mulher incrívell, representou !'),
(32, 78, 2, 'Esse post merece mais reconhecimento! Super relevante e útil. 👍'),
(33, 75, 2, 'Muito bom! Vou compartilhar com meus amigos, eles vão adorar! 😊'),
(34, 77, 4, 'Cada vesz mais as mudanças são agravadas!'),
(35, 96, 4, 'Vai deixar saudades querido Papa :('),
(36, 99, 3, 'Assunto muito interessante'),
(37, 99, 1, 'Que clima imprevisível !'),
(38, 75, 3, 'Parabéns!!!! Adorei a interação!!'),
(39, 75, 3, 'Very good😍'),
(40, 99, 5, 'Muito bomm!'),
(41, 75, 5, 'Boaaa'),
(42, 83, 5, 'Boaaa'),
(43, 101, 3, 'Adorei, essa revista ficou incrível'),
(44, 102, 3, 'Incrível incrível!!');

-- --------------------------------------------------------

--
-- Estrutura para tabela `likes`
--

CREATE TABLE `likes` (
  `id_usuario_like` int(11) NOT NULL,
  `id_post_like` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `likes`
--

INSERT INTO `likes` (`id_usuario_like`, `id_post_like`) VALUES
(1, 89),
(1, 82),
(1, 73),
(4, 92),
(1, 95),
(1, 78),
(1, 75),
(1, 76),
(1, 74),
(1, 81),
(1, 92),
(2, 96),
(4, 75),
(4, 95),
(2, 76),
(2, 95),
(4, 74),
(2, 92),
(2, 78),
(4, 77),
(2, 75),
(4, 96),
(4, 89),
(1, 99),
(3, 99),
(3, 81),
(3, 75),
(3, 77),
(5, 99),
(3, 82),
(5, 75),
(5, 83),
(3, 101),
(3, 102);

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `id` int(3) NOT NULL,
  `perfil` varchar(40) NOT NULL,
  `postar` enum('S','N') NOT NULL,
  `editar` enum('S','N') NOT NULL,
  `solicitar_post` enum('S','N') NOT NULL,
  `editar_usuarios` enum('S','N') NOT NULL,
  `visionar_post` enum('S','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `permissoes`
--

INSERT INTO `permissoes` (`id`, `perfil`, `postar`, `editar`, `solicitar_post`, `editar_usuarios`, `visionar_post`) VALUES
(1, 'visitante', 'N', 'N', 'N', 'N', 'N'),
(2, 'aluno', 'N', 'N', 'S', 'N', 'N'),
(3, 'professor', 'S', 'S', 'N', 'N', 'S'),
(4, 'admin', 'S', 'S', 'N', 'S', 'N');

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id_solicitacoes` int(11) NOT NULL,
  `grupo` int(11) NOT NULL,
  `id_usuario_solicitacoes` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `subtitulo` varchar(150) NOT NULL,
  `tema` varchar(100) NOT NULL,
  `conteudo` text NOT NULL,
  `img` varchar(225) DEFAULT NULL,
  `status` enum('pendente','aprovado','rejeitado','revisar') NOT NULL,
  `data_solicitacao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`id_solicitacoes`, `grupo`, `id_usuario_solicitacoes`, `titulo`, `subtitulo`, `tema`, `conteudo`, `img`, `status`, `data_solicitacao`) VALUES
(73, 1, 4, 'Uso da Arte em diferentes épocas', 'Técnicas de Arte', 'Artes', '🎨 Da Caverna ao Digital: Como a Arte Acompanhou a História da Humanidade\r\n\r\nAo longo dos séculos, a arte tem sido muito mais do que expressão estética — ela é um espelho do tempo. Desde os primeiros registros nas paredes das cavernas até instalações interativas feitas com inteligência artificial, a arte evoluiu lado a lado com a história da humanidade, adaptando-se às transformações culturais, políticas e tecnológicas de cada época. E, acima de tudo, sempre refletindo as dores, as crenças, os sonhos e as lutas de diferentes comunidades 🌍.\r\n\r\n\r\n🪨 Pré-História\r\n\r\nNa Pré-História, os primeiros artistas — anônimos caçadores e coletores — deixaram registros com pigmentos naturais em paredes de cavernas. Eram cenas de caça, animais e símbolos ligados à sobrevivência e à espiritualidade. Mais do que imagens, esses traços representavam rituais e crenças de povos que viviam em harmonia com a natureza 🌿.\r\n\r\n\r\n🏺 Antiguidade\r\n\r\nCom o avanço das civilizações antigas, como Egito, Grécia e Roma, a arte ganhou nova função: servir ao poder e à religião. No Egito, as paredes dos templos eram preenchidas com hieróglifos e figuras pintadas com têmpera, representando deuses, reis e a vida após a morte. Na Grécia, esculturas em mármore exaltavam o corpo humano idealizado, enquanto os romanos dominaram os mosaicos e os afrescos com cenas mitológicas e do cotidiano.\r\n\r\n\r\n⛪ Idade Média\r\n\r\nNa Idade Média, a arte se concentrou nos templos e catedrais, dominada pelo simbolismo cristão ✝️. Iluminuras, vitrais e afrescos serviam como “Bíblias visuais” para uma população em grande parte analfabeta. As técnicas eram rígidas, e as obras obedeciam a uma hierarquia espiritual, com pouca preocupação com realismo, mas intensa devoção.\r\n\r\n\r\n🧠 Renascimento\r\n\r\nO Renascimento revolucionou tudo. Na Europa dos séculos XIV a XVI, artistas como Leonardo da Vinci e Michelangelo passaram a retratar o ser humano como centro do universo. Com o uso da perspectiva, do claro-escuro e do estudo anatômico, a arte se aproximou da ciência e da filosofia. A técnica do óleo sobre tela ganhou força, e o artista passou a ser visto como um gênio criador.\r\n\r\n\r\n🎭 Barroco ao Romantismo\r\n\r\nNos séculos seguintes, o Barroco trouxe dramaticidade, com cenas religiosas intensas, contrastes de luz e sombra e movimento. Já no Neoclassicismo e Romantismo, a arte refletiu o espírito de épocas de revoluções e mudanças sociais: ora retomando valores da antiguidade clássica, ora exaltando emoções, natureza e liberdade.\r\n\r\n\r\n🌅 Século XIX – Realismo ao Pós-Impressionismo\r\n\r\nO Realismo buscou retratar a vida comum, enquanto o Impressionismo rompeu com os ateliês e levou os artistas para pintar ao ar livre 🎨🌳, captando a luz e o instante. Essa liberdade abriu caminho para o século XX, onde a arte explodiu em movimentos como Cubismo, Surrealismo, Expressionismo e Dadaísmo. As técnicas se diversificaram: colagens, assemblagens, pinturas abstratas e experimentações que refletiam guerras, rupturas e mudanças sociais.\r\n\r\n\r\n🖥️ Arte Contemporânea\r\n\r\nNos dias de hoje, a arte contemporânea mistura performance, vídeo, instalações, realidade virtual e redes sociais. O artista contemporâneo transforma ideias em obras, questiona normas e se conecta diretamente com temas como identidade, meio ambiente, política e tecnologia.\r\n\r\n\r\n🎨 Conclusão\r\nA arte, ao longo do tempo, tem sido uma linguagem universal — sempre se reinventando, sempre dialogando com as necessidades, angústias e esperanças de quem a produz e de quem a contempla. Das pedras às telas digitais, do sagrado ao social, a arte continua sendo uma das formas mais poderosas de contar a história da humanidade 📖✨.', NULL, 'aprovado', '2025-04-23 16:33:43'),
(74, 2, 4, 'Frida Khalo - História de vida', 'Ícone mexicana, representatividade das mulheres na arte', 'Artes', 'A dor que virou arte e o legado imortal \r\n\r\nFrida Kahlo, nascida em 6 de julho de 1907, em Coyoacán, no México, é muito mais do que uma pintora renomada — é um símbolo de resistência, identidade e liberdade. Sua vida, marcada por dores físicas e emocionais, foi transformada em arte com uma intensidade que ainda hoje ecoa em todo o mundo. Desde a infância, Frida enfrentou desafios severos. Aos seis anos, contraiu poliomielite, o que deixou sequelas em sua perna direita. Mais tarde, aos 18 anos, um trágico acidente de ônibus mudou radicalmente o rumo de sua vida. Durante a dolorosa recuperação, confinada à cama, ela começou a pintar autorretratos, utilizando um espelho preso ao dossel de sua cama como recurso. Esses retratos se tornaram uma forma de expressar tudo o que sentia, do sofrimento físico à angústia emocional, criando uma linguagem visual única, intensa e profundamente pessoal.\r\n\r\nFrida não se considerava surrealista, como muitos críticos a classificavam. Ela dizia que não pintava sonhos, mas a sua própria realidade. E, de fato, suas obras são espelhos fiéis de sua vida interior — uma vida marcada por amores conturbados, especialmente seu relacionamento com o também artista Diego Rivera, e por uma constante batalha com o próprio corpo. Suas telas exploram temas como a dor, a morte, a infertilidade, o aborto, a identidade feminina, a cultura mexicana e o sentimento de não pertencimento. Cada pincelada sua era uma forma de resistir e de afirmar sua existência diante de um mundo que muitas vezes tentou silenciá-la.\r\n\r\nAlém da arte, Frida Kahlo foi uma mulher politicamente engajada. Atuante no Partido Comunista Mexicano, defendeu causas sociais e posicionou-se ao lado dos marginalizados. Seu envolvimento político e seu estilo de vida livre desafiaram os padrões conservadores da época. Ela também foi uma das primeiras artistas latino-americanas a alcançar reconhecimento internacional em vida, expondo inclusive no Louvre, em Paris — um feito inédito para uma mulher latino-americana naquela época.\r\n\r\nMesmo após sua morte, em 13 de julho de 1954, Frida continua presente. Sua casa, “La Casa Azul”, onde nasceu e faleceu, foi transformada em museu e atrai milhares de visitantes de todo o mundo. Seu diário íntimo, cartas, roupas e objetos pessoais revelam não só a artista, mas a mulher forte, vulnerável e autêntica por trás da imagem. Hoje, Frida é lembrada como um ícone feminista, uma referência LGBTQIA+ e um símbolo da cultura e da resistência mexicana. Sua imagem — com sobrancelhas marcantes, flores nos cabelos e roupas tradicionais — virou ícone pop, estampando murais, camisetas, filmes e exposições.\r\n\r\nMais do que uma artista, Frida Kahlo é memória viva. Sua obra e sua vida mostram que da dor pode nascer beleza, e que da luta por ser quem se é pode surgir um legado eterno. Ela nos ensinou, com pincéis e coragem, que não se trata apenas de sobreviver, mas de transformar o sofrimento em algo que inspire o mundo.', '15948482115f0f73d3cff5e_1594848211_3x2_md.jpg', 'aprovado', '2025-04-23 16:41:20'),
(75, 3, 4, 'O uso da língua culta ', 'A Importância da Língua Portuguesa no Desenvolvimento Acadêmico e Profissional ', 'Língua Portuguesa', 'A proficiência na língua portuguesa tem se mostrado cada vez mais essencial para o sucesso acadêmico e profissional no Brasil. Estudos recentes destacam que a habilidade de se comunicar de forma clara e eficaz é um diferencial significativo no mercado de trabalho atual.  \r\n\r\nDe acordo com especialistas, profissionais que dominam a norma culta do português demonstram cuidado e preparação, o que os torna mais atraentes aos olhos dos recrutadores durante processos seletivos. Além disso, uma comunicação eficaz é considerada uma ferramenta essencial para alcançar produtividade e manter relações de trabalho sólidas em todos os níveis de uma organização. No âmbito educacional, iniciativas têm sido implementadas para reforçar o ensino da língua portuguesa. Por exemplo, a Política Nacional de Recuperação das Aprendizagens na Educação Básica, conhecida como RECUPERA MAIS BRASIL, foi lançada para recuperar as aprendizagens afetadas pela pandemia. Essa política alcançou mais de 2.400 municípios brasileiros, com mais de 5,6 milhões de estudantes cadastrados e mais de 9 milhões de testes avaliativos aplicados. Esses dados reforçam a importância contínua do domínio da língua portuguesa, não apenas como ferramenta de comunicação, mas também como um pilar fundamental para o desenvolvimento educacional e profissional no país. \r\n\r\nFontes utilizadas: Nube - Núcleo Brasileiro de Estágios+1Todas as Respostas+1Unisanta Cruz \r\n\r\nWikipédia, a enciclopédia livre ', 'reserve-na-biblioteca-com-livro-aberto_1150-5920.avif', 'aprovado', '2025-04-23 16:41:20'),
(76, 4, 4, 'Técnicas de Arte na Realidade', 'Alunos performam situações cotidianas que evidenciam a desigualdade', 'Artes', 'Alunos do 3º Ano do SESI Presidente Prudente Realizam Trote Solidário e Visita ao Lar de Idosos em Regente Feijó \r\n\r\nOs alunos do 3º ano do SESI Presidente Prudente protagonizaram uma ação solidária que marcou a comunidade e emocionou a todos os envolvidos. Como parte do tradicional Trote Solidário, a turma organizou uma campanha de arrecadação de doações, reunindo produtos de higiene e outros itens de limpeza, que foram entregues ao Lar de Idosos de Regente Feijó durante uma visita especial no dia 20/03. \r\n\r\nAlém da entrega das doações, o momento mais significativo da ação foi a interação entre os estudantes e os idosos. “Para nós, jovens inexperientes na vida, estar ao lado de pessoas com tanta história e sabedoria foi uma verdadeira lição” . As conversas foram repletas de aprendizado, troca de experiências e reflexões sobre o passado e o presente. Enquanto os idosos compartilhavam memórias e conselhos, nós, estudantes, percebíamos a importância de valorizar o tempo, a empatia e o cuidado com aqueles que vieram antes de nós. \r\n\r\nA visita foi marcada por momentos de alegria, dança, músicas, sorrisos e muita emoção. A felicidade no olhar dos idosos ao receberem carinho e atenção demonstrou que pequenas atitudes podem fazer uma grande diferença. Mais do que um simples trote, essa experiência nos transformou, reforçou que a solidariedade vai muito além de doações materiais: é sobre presença, empatia e respeito pelo próximo. mostrando o impacto positivo que podemos gerar na vida dos outros. \r\n\r\nA iniciativa foi registrada em um vídeo publicado no perfil oficial do SESI Presidente Prudente no Instagram, onde é possível ver os momentos de troca e afeto entre os estudantes e os residentes do lar. \r\n\r\nPara assistir ao vídeo completo e acompanhar mais ações como essa, visite o perfil do SESI Presidente Prudente no Instagram. ', 'retrato-de-femininas-mime-ficar-em-auditorio_23-2147891550.jpg', 'aprovado', '2025-04-23 16:47:30'),
(77, 5, 5, 'Desastres Naturais', 'Quando a Natureza Fala Alto', 'Geografia', 'Introdução\r\n\r\nDesastres naturais fazem parte da dinâmica do planeta Terra. Eles são eventos causados por forças da natureza que, muitas vezes, acontecem de forma inesperada e têm impacto direto na vida das pessoas, no meio ambiente e nas economias locais e globais. Entender suas causas, tipos e consequências é essencial para prevenir danos e salvar vidas.\r\n\r\nO que são desastres naturais?\r\n\r\nDesastres naturais são eventos extremos que ocorrem por ações naturais, como chuvas intensas, movimentação de placas tectônicas, erupções vulcânicas, entre outros. Esses eventos podem se tornar desastres quando afetam populações humanas, especialmente em locais vulneráveis ou mal preparados.\r\n\r\nPrincipais tipos de desastres naturais\r\n\r\n1. Terremotos\r\n\r\nSão tremores provocados pelo movimento das placas tectônicas. Podem causar destruição de construções e gerar tsunamis, principalmente em regiões como o Japão, Chile e Indonésia.\r\n\r\n2. Tsunamis\r\n\r\nOndas gigantes geradas por terremotos submarinos ou erupções vulcânicas. Avançam sobre o litoral com grande velocidade, destruindo tudo em seu caminho.\r\n\r\n3. Vulcões\r\n\r\nErupções vulcânicas lançam lava, cinzas e gases tóxicos. Além do perigo direto, as cinzas podem prejudicar a saúde humana e a agricultura.\r\n\r\n4. Enchentes\r\n\r\nCausadas por chuvas intensas, podem ser agravadas pelo entupimento de bueiros, desmatamento e ocupações irregulares. São frequentes em cidades brasileiras.\r\n\r\n5. Secas\r\n\r\nA escassez prolongada de chuvas afeta plantações, abastecimento de água e gera prejuízos econômicos. No Brasil, o semiárido nordestino é uma região recorrente.\r\n\r\n6. Deslizamentos de terra\r\n\r\nOcorrem em áreas de encostas, geralmente após chuvas fortes. Podem destruir casas e vitimar pessoas em locais com habitações precárias.\r\n\r\nConsequências dos desastres naturais\r\n\r\nMortes e feridos\r\n\r\nDeslocamento de populações\r\n\r\nPerda de moradias e bens materiais\r\n\r\nPrejuízos econômicos e sociais\r\n\r\nDanos ao meio ambiente\r\n\r\nProblemas de saúde e saneamento\r\n\r\nPrevenção e preparação\r\n\r\nA redução dos riscos passa por:\r\n\r\nMonitoramento climático e geológico (ex: Defesa Civil, INMET, CEMADEN)\r\n\r\nEducação ambiental e geográfica nas escolas\r\n\r\nPlanejamento de alerta e evacuação\r\n\r\nPreservação ambiental, como o reflorestamento\r\n\r\nO papel da Geografia\r\n\r\nA Geografia é fundamental para entender os processos naturais e a interação humana com o meio ambiente. O conhecimento geográfico ajuda na prevenção de desastres e na construção de uma sociedade mais resiliente e preparada.\r\n\r\nAtividades sugeridas\r\n\r\nMapa interativo: localize no mapa-múndi onde ocorrem os principais desastres naturais.\r\n\r\nEstudo de caso: pesquise sobre o desastre de Brumadinho (MG, 2019) ou o terremoto no Haiti (2010).\r\n\r\nDebate em sala: como podemos ajudar nossa comunidade a se prevenir contra enchentes ou deslizamentos?\r\n\r\nCuriosidades\r\n\r\nO terremoto mais forte registrado foi o do Chile, em 1960, com magnitude 9,5.\r\n\r\nNo Brasil, os desastres mais comuns são chuvas intensas, enchentes e deslizamentos.\r\n\r\nO CEMADEN monitora riscos naturais em tempo real e emite alertas para prevenção.\r\n\r\nConclusão\r\n\r\nOs desastres naturais não podem ser evitados, mas os danos podem ser minimizados com educação, planejamento e responsabilidade. A Geografia escolar tem um papel vital na formação de jovens conscientes e preparados para os desafios do mundo natural, urbano e habitações seguras', NULL, 'aprovado', '2025-04-23 17:38:48'),
(78, 6, 5, 'Desastres Naturais', 'Quando a Natureza Fala Alto', 'Geografia', 'Introdução\r\n\r\nDesastres naturais fazem parte da dinâmica do planeta Terra. Eles são eventos causados por forças da natureza que, muitas vezes, acontecem de forma inesperada e têm impacto direto na vida das pessoas, no meio ambiente e nas economias locais e globais. Entender suas causas, tipos e consequências é essencial para prevenir danos e salvar vidas.\r\n\r\nO que são desastres naturais?\r\n\r\nDesastres naturais são eventos extremos que ocorrem por ações naturais, como chuvas intensas, movimentação de placas tectônicas, erupções vulcânicas, entre outros. Esses eventos podem se tornar desastres quando afetam populações humanas, especialmente em locais vulneráveis ou mal preparados.\r\n\r\nPrincipais tipos de desastres naturais\r\n\r\n1. Terremotos\r\n\r\nSão tremores provocados pelo movimento das placas tectônicas. Podem causar destruição de construções e gerar tsunamis, principalmente em regiões como o Japão, Chile e Indonésia.\r\n\r\n2. Tsunamis\r\n\r\nOndas gigantes geradas por terremotos submarinos ou erupções vulcânicas. Avançam sobre o litoral com grande velocidade, destruindo tudo em seu caminho.\r\n\r\n3. Vulcões\r\n\r\nErupções vulcânicas lançam lava, cinzas e gases tóxicos. Além do perigo direto, as cinzas podem prejudicar a saúde humana e a agricultura.\r\n\r\n4. Enchentes\r\n\r\nCausadas por chuvas intensas, podem ser agravadas pelo entupimento de bueiros, desmatamento e ocupações irregulares. São frequentes em cidades brasileiras.\r\n\r\n5. Secas\r\n\r\nA escassez prolongada de chuvas afeta plantações, abastecimento de água e gera prejuízos econômicos. No Brasil, o semiárido nordestino é uma região recorrente.\r\n\r\n6. Deslizamentos de terra\r\n\r\nOcorrem em áreas de encostas, geralmente após chuvas fortes. Podem destruir casas e vitimar pessoas em locais com habitações precárias.\r\n\r\nConsequências dos desastres naturais\r\n\r\nMortes e feridos\r\n\r\nDeslocamento de populações\r\n\r\nPerda de moradias e bens materiais\r\n\r\nPrejuízos econômicos e sociais\r\n\r\nDanos ao meio ambiente\r\n\r\nProblemas de saúde e saneamento\r\n\r\nPrevenção e preparação\r\n\r\nA redução dos riscos passa por:\r\n\r\nMonitoramento climático e geológico (ex: Defesa Civil, INMET, CEMADEN)\r\n\r\nEducação ambiental e geográfica nas escolas\r\n\r\nPlanejamento de alerta e evacuação\r\n\r\nPreservação ambiental, como o reflorestamento\r\n\r\nO papel da Geografia\r\n\r\nA Geografia é fundamental para entender os processos naturais e a interação humana com o meio ambiente. O conhecimento geográfico ajuda na prevenção de desastres e na construção de uma sociedade mais resiliente e preparada.\r\n\r\nAtividades sugeridas\r\n\r\nMapa interativo: localize no mapa-múndi onde ocorrem os principais desastres naturais.\r\n\r\nEstudo de caso: pesquise sobre o desastre de Brumadinho (MG, 2019) ou o terremoto no Haiti (2010).\r\n\r\nDebate em sala: como podemos ajudar nossa comunidade a se prevenir contra enchentes ou deslizamentos?\r\n\r\nCuriosidades\r\n\r\nO terremoto mais forte registrado foi o do Chile, em 1960, com magnitude 9,5.\r\n\r\nNo Brasil, os desastres mais comuns são chuvas intensas, enchentes e deslizamentos.\r\n\r\nO CEMADEN monitora riscos naturais em tempo real e emite alertas para prevenção.\r\n\r\nConclusão\r\n\r\nOs desastres naturais não podem ser evitados, mas os danos podem ser minimizados com educação, planejamento e responsabilidade. A Geografia escolar tem um papel vital na formação de jovens conscientes e preparados para os desafios do mundo natural, urbano e habitações seguras', 'desastres.jpg', 'aprovado', '2025-04-23 17:40:26'),
(79, 7, 5, 'Inglês ao Redor do Mundo', 'Curiosidades e Expressões Idiomáticas', 'Língua Inglesa', 'Introdução\r\n\r\nO inglês é uma das línguas mais faladas do mundo e possui uma enorme variedade de sotaques, vocabulários e expressões. Cada país que fala inglês possui sua própria identidade linguística, influenciada por aspectos históricos, culturais e sociais. Nesta reportagem, vamos conhecer curiosidades sobre alguns desses países e comparar expressões idiomáticas divertidas e curiosas.\r\n\r\nPaíses falantes do inglês: Curiosidades\r\n\r\nEstados Unidos (USA)\r\n\r\nTem mais de 300 milhões de falantes nativos.\r\n\r\nO inglês americano varia muito de região para região: \"y\'all\" no sul, \"dude\" na Califórnia.\r\n\r\nÉ o berço de expressões populares como \"break the ice\" (quebrar o gelo).\r\n\r\nReino Unido (UK)\r\n\r\nEngloba Inglaterra, Escócia, País de Gales e Irlanda do Norte.\r\n\r\nPossui dezenas de sotaques diferentes, como o \"cockney\" de Londres e o \"scottish\" da Escócia.\r\n\r\nA origem de muitas expressões idiomáticas clássicas do inglês vem daqui.\r\n\r\nAustrália\r\n\r\nConhecido pelo inglês descontraído e cheio de gírias.\r\n\r\nOs australianos costumam abreviar palavras: \"arvo\" (afternoon), \"brekkie\" (breakfast).\r\n\r\nUma das expressões mais comuns: \"No worries\" (Sem problemas).\r\n\r\nCanadá\r\n\r\nInglês e francês são idiomas oficiais.\r\n\r\nTem vocabulário próprio em algumas regiões, como \"toque\" (gorros de frio).\r\n\r\nSão conhecidos por sua educação e cordialidade: \"Sorry!\" é uma palavra muito usada.\r\n\r\nÍndia\r\n\r\nUm dos maiores países com falantes de inglês como segunda língua.\r\n\r\nO \"Indian English\" tem influências do hindi e outras línguas locais.\r\n\r\nPalavras como \"prepone\" (adiantar, oposto de \"postpone\") são comuns no uso local.\r\n\r\nComparativo de expressões idiomáticas\r\n\r\nExpressão em inglês\r\n\r\nTradução literal\r\n\r\nSignificado\r\n\r\nEquivalente em português\r\n\r\nIt\'s raining cats and dogs\r\n\r\nEstá chovendo gatos e cachorros\r\n\r\nChuva muito forte\r\n\r\nEstá chovendo canivete\r\n\r\nBreak the ice\r\n\r\nQuebrar o gelo\r\n\r\nIniciar uma conversa\r\n\r\nQuebrar o gelo\r\n\r\nSpill the beans\r\n\r\nDerramar os feijões\r\n\r\nRevelar um segredo\r\n\r\nDar com a língua nos dentes\r\n\r\nHit the books\r\n\r\nAcertar os livros\r\n\r\nEstudar bastante\r\n\r\nEnfiar a cara nos livros\r\n\r\nPiece of cake\r\n\r\nPedaço de bolo\r\n\r\nAlgo fácil\r\n\r\nMoleza\r\n\r\nAtividades sugeridas\r\n\r\nMapa mundial linguístico: localize os países falantes do inglês.\r\n\r\nCrie um minidicionário de expressões idiomáticas com desenhos ilustrativos.\r\n\r\nDebate cultural: Qual sotaque você acha mais interessante e por quê?\r\n\r\nConclusão\r\n\r\nAprender inglês é muito mais do que decorar regras gramaticais. É mergulhar em uma cultura rica, diversa e cheia de curiosidades. Ao conhecer as diferenças entre os países e suas expressões, ampliamos nossa compreensão do mundo e nos tornamos aprendizes mais atentos e conscientes.', 'estados unidos.jpg', 'aprovado', '2025-04-23 17:41:40'),
(81, 9, 5, 'Divisão em Partes Desiguais', 'A Proporção Inversa com Três Termos', 'Matemática', 'Introdução\r\n\r\nNa vida real, nem sempre os recursos são divididos igualmente. Quando queremos favorecer quem tem menos ou penalizar quem tem mais, usamos a proporção inversa. Essa ferramenta matemática é útil para dividir valores de forma justa, de acordo com critérios específicos. Vamos entender como isso funciona com três termos.\r\n\r\nO que é proporção inversa?\r\n\r\nNa proporção direta, quanto mais, mais. Na proporção inversa, é o contrário: quanto mais um valor, menos o outro.\r\n\r\nPor exemplo: se três trabalhadores com eficiências diferentes precisam dividir uma tarefa, quem é mais eficiente (ou mais rápido) recebe uma parte menor do tempo, pois faz mais em menos tempo.\r\n\r\nDividindo em três partes desiguais (inversamente proporcionais)\r\n\r\nImagine que R$ 600,00 devem ser divididos entre João, Ana e Pedro, de forma inversamente proporcional aos números 2, 3 e 5.\r\n\r\nPasso a passo:\r\n\r\nInversos: 1/2, 1/3 e 1/5\r\n\r\nColoque todos no mesmo denominador:\r\n\r\nmmc(2,3,5) = 30\r\n\r\n1/2 = 15/30\r\n\r\n1/3 = 10/30\r\n\r\n1/5 = 6/30\r\n\r\nUse os numeradores (15, 10 e 6) como base da divisão:\r\n\r\nSoma: 15 + 10 + 6 = 31 partes\r\n\r\nCada parte vale: 600 / 31 ≈ R$ 19,35\r\n\r\nJoão: 15 x 19,35 = R$ 290,25\r\n\r\nAna: 10 x 19,35 = R$ 193,50\r\n\r\nPedro: 6 x 19,35 = R$ 116,25\r\n\r\nResultado:\r\n\r\nJoão: R$ 290,25\r\n\r\nAna: R$ 193,50\r\n\r\nPedro: R$ 116,25\r\n\r\nAplicando na vida real\r\n\r\nRepartição de tarefas: quanto menos tempo disponível, maior a parte da tarefa.\r\n\r\nDistribuição de recursos: ajuda mais quem tem menos.\r\n\r\nAvaliações educativas: pode ser usada para dividir pontos de forma balanceada entre diferentes níveis de dificuldade.\r\n\r\nAtividades sugeridas\r\n\r\nProblema contextualizado: Três amigos querem dividir um trajeto de carro conforme o consumo de gasolina dos seus carros (quanto mais econômico, menor a parte).\r\n\r\nCálculo em grupo: propor valores para dividir um prêmio entre pessoas com diferentes contribuições.\r\n\r\nDesafio matemático: criar um problema de divisão inversa com três termos e trocar com colegas para resolver.\r\n\r\nConclusão\r\n\r\nCompreender a proporção inversa permite resolver problemas de divisão desigual de forma justa e lógica. Essa ferramenta está presente em diversas situações cotidianas e nos ajuda a desenvolver o raciocínio matemático e crítico.', 'matematica.webp', 'aprovado', '2025-04-23 17:50:12'),
(82, 10, 4, 'Enem, inscrição indispensável !', 'Ainda neste ano alunos do Ensino Médio poderão realizar a prova.', 'Língua Portuguesa', '📚 ENEM 2025: Inscrições Abertas! A Prova Que Transforma Sonhos em Realidade.\r\n\r\n\"Em breve, neste ano, as inscrições para o ENEM (Exame Nacional do Ensino Médio) estarão abertas. Adolescentes do 1º e 2º ano podem participar como treineiros, enquanto os estudantes do 3º ano poderão realizar a prova oficialmente. Aplicado em todo o país, o ENEM oferece inúmeras oportunidades acadêmicas.\"\r\n\r\nComo se inscrever:\r\nPara participar, o candidato deve acessar a Página do Participante no site oficial do INEP:\r\n\r\n👉 enem.inep.gov.br/participante\r\n\r\nÉ necessário ter CPF, documento de identidade e um e-mail válido. A taxa de inscrição é de R$ 85,00, com pagamento até o dia 22 de maio via boleto, Pix ou cartão de crédito. Alunos que solicitaram isenção e tiveram o pedido aprovado não precisam pagar a taxa ✅.\r\n\r\n📆 Datas importantes do ENEM 2025\r\n\r\nInscrições: 6 a 17 de maio\r\nPagamento da taxa: até 22 de maio\r\nProvas: 3 e 10 de novembro\r\nDivulgação do gabarito: 20 de novembro\r\nResultado final: janeiro de 2026 (data a confirmar)\r\n\r\n🎯 Por que o ENEM é tão importante?\r\n\r\nMais do que um exame, o ENEM se tornou uma ferramenta de inclusão social e acesso ao ensino superior no Brasil. Ele permite que milhões de estudantes concorram a vagas em universidades públicas e privadas de forma unificada — eliminando a necessidade de prestar dezenas de vestibulares diferentes.\r\n\r\nCom a nota do ENEM, o estudante pode:\r\n\r\n°Entrar em universidades públicas pelo SISU (Sistema de Seleção Unificada) 🏛️\r\n\r\n°Conquistar bolsas de até 100% em faculdades privadas através do PROUNI (Programa Universidade para Todos) 🎓\r\n\r\n°Solicitar financiamento estudantil com o FIES (Fundo de Financiamento Estudantil) 💰\r\n\r\n°Estudar em universidades de Portugal e outros países que aceitam a nota do ENEM como critério de seleção internacional 🌍\r\n\r\nAlém disso, o ENEM é utilizado como forma de certificação do ensino médio para quem não concluiu os estudos na idade regular. Ou seja, é uma segunda chance para muitos.\r\n\r\n📚 E os vestibulares tradicionais?\r\n\r\nEmbora o ENEM seja o principal exame de ingresso no ensino superior no Brasil, os vestibulares ainda continuam existindo, especialmente em universidades estaduais como a USP (Fuvest), Unicamp (Comvest), UERJ e universidades particulares.\r\n\r\nEles têm formatos e critérios próprios, e muitos estudantes prestam tanto o ENEM quanto esses vestibulares, ampliando as chances de aprovação. Alguns vestibulares cobram redações mais específicas, exigem obras literárias obrigatórias e provas com estilos diferentes. Por isso, preparar-se para ambos os modelos é um diferencial estratégico 🔍📖.\r\n\r\nHistória que inspira: do pão à medicina\r\n\r\nAna Clara, de 18 anos, é filha de uma atendente e de um pedreiro. Moradora de uma pequena cidade em Minas Gerais, ela conciliava o ensino médio noturno com o trabalho em uma padaria. Estudava com apostilas doadas e vídeos gratuitos no YouTube. Em 2024, após fazer o ENEM, conquistou uma vaga com bolsa integral para medicina na Universidade Federal de Juiz de Fora. “Achei que nunca teria chance. O ENEM abriu a porta que parecia impossível”, conta emocionada.\r\n\r\n🎯 Se você sonha em cursar uma faculdade, o primeiro passo é a inscrição.\r\n', 'images.jpg', 'aprovado', '2025-04-23 17:52:20'),
(83, 11, 5, 'Corpo, Mente e Sociedade', 'Os Vícios, a Tecnologia e os Hormônios', 'Biologia', 'Os Vícios, a Tecnologia e os Hormônios\r\nIntrodução\r\n\r\nVivemos em uma era em que o acesso à tecnologia, o estresse cotidiano e os apelos sociais influenciam diretamente a nossa saúde física e mental. Nesta matéria, vamos entender como funcionam os vícios, qual o impacto da tecnologia sobre o organismo e o papel dos hormônios no comportamento humano.\r\n\r\nO que são vícios?\r\n\r\nVício é uma dependência, geralmente causada por uma substância (como álcool, cigarro, drogas) ou comportamento (como jogos ou redes sociais), que interfere negativamente na vida da pessoa. O vício é sustentado por alterações no sistema de recompensa do cérebro, ligado ao prazer e à liberação de dopamina.\r\n\r\nPrincipais tipos de vício\r\n\r\nQuímicos: álcool, tabaco, maconha, cocaína, entre outros.\r\n\r\nComportamentais: jogos eletrônicos, redes sociais, compras, alimentação compulsiva.\r\n\r\nA tecnologia e seus efeitos no corpo e na mente\r\n\r\nA tecnologia facilita a vida, mas seu uso excessivo pode causar problemas:\r\n\r\nDistúrbios do sono: a luz azul das telas inibe a melatonina, dificultando o sono.\r\n\r\nAnsiedade e depressão: o uso abusivo de redes sociais está associado a baixa autoestima.\r\n\r\nSedentarismo: muitas horas em frente às telas diminuem a atividade física.\r\n\r\nIsolamento social: substitui relações reais por virtuais.\r\n\r\nTecnologia como aliada\r\n\r\nQuando usada com consciência, pode ajudar com aplicativos de saúde, meditação, controle de sono e conexão com familiares.\r\n\r\nHormônios e comportamento humano\r\n\r\nHormônios são substâncias químicas que regulam diversas funções no corpo, como o crescimento, o metabolismo e as emoções.\r\n\r\nPrincipais hormônios ligados ao comportamento\r\n\r\nDopamina: prazer e recompensa.\r\n\r\nSerotonina: humor e bem-estar.\r\n\r\nAdrenalina: prepara o corpo para reações de alerta e estresse.\r\n\r\nCortisol: relacionado ao estresse.\r\n\r\nOxitocina: ligado ao afeto e à empatia.\r\n\r\nO desequilíbrio hormonal pode levar a alterações de humor, agressividade, ansiedade e outras condições mentais.\r\n\r\nAtividades sugeridas\r\n\r\nDebate em sala: Como equilibrar o uso da tecnologia?\r\n\r\nCartaz educativo: mostre os efeitos de drogas no corpo humano.\r\n\r\nSimulação: crie uma peça de teatro sobre como os hormônios afetam nosso dia a dia.\r\n\r\nConclusão\r\n\r\nEntender como os vícios, a tecnologia e os hormônios atuam em nosso corpo e mente é essencial para viver com mais equilíbrio e consciência. A Biologia nos oferece ferramentas para compreender esses processos e buscar uma vida mais saudável.', NULL, 'aprovado', '2025-04-23 17:51:33'),
(89, 13, 4, 'A Califórnia em Chamas', 'Incêndios Devastam Malibu e Pacific Palisades', 'Geografia', 'Incêndios devastam regiões Malibu e Pacific Palisades, o fogo consumiu tudo.\r\nLOS ANGELES, abril de 2025 – Uma das piores crises ambientais da história recente da Califórnia transformou paisagens paradisíacas em cenários de destruição. As regiões costeiras de Malibu e Pacific Palisades, conhecidas mundialmente por suas mansões de luxo, natureza exuberante e celebridades, foram devastadas por incêndios florestais que duraram semanas e deixaram um rastro quase irreversível de destruição.\r\nNo final de 2024, com a vegetação seca como pólvora, o incêndio Franklin começou em Malibu. Um raio, um cabo elétrico rompido — ninguém sabe ao certo. Mas o que se sabe é que o vento soprou forte e levou as chamas ladeira abaixo, direto para as casas que beiram o Pacífico.\r\n\r\n\r\n\r\n🚒 O esforço heróico e a esperança que sobrevive\r\nMais de 100 mil pessoas foram evacuadas. Bombeiros de várias partes dos EUA — e até do México e do Canadá — trabalharam dia e noite, dormindo no chão, se revezando entre o cansaço e o medo. Governos locais entraram em estado de alerta máximo. A Guarda Nacional patrulhou bairros vazios, como em zonas de guerra.\r\n\r\nMas em meio à tragédia, algo sobreviveu: a solidariedade. Pessoas abriram as portas de suas casas, doaram roupas, alimentos, tempo. Comunidades inteiras se uniram para reconstruir — não só o que foi destruído, mas o sentimento de pertencimento.\r\n\r\n_Famosos também foram atingidos\r\n\r\nAs chamas não pouparam nem mesmo os moradores ilustres da região. A socialite Paris Hilton perdeu sua mansão em Malibu ao vivo, enquanto uma emissora de TV fazia uma cobertura aérea. Em lágrimas, ela declarou que “tudo o que construí, todas as lembranças da minha infância, se foram em minutos”. Outros nomes como Mel Gibson, Milo Ventimiglia e Liev Schreiber também tiveram suas propriedades atingidas.\r\n\r\n', '1489710-me-0108-palisades-fire-wjs.jpg', 'aprovado', '2025-04-24 17:41:30'),
(90, 13, 4, 'A Califórnia em Chamas', 'Incêndios Devastam Malibu e Pacific Palisades', 'Geografia', 'Dias depois, já em 2025, um novo monstro surgia nas colinas dos Palisades: o incêndio Palisades, que, em poucos dias, virou o maior da história moderna de Los Angeles. Imóveis históricos, igrejas centenárias, escolas e restaurantes frequentados por astros de Hollywood viraram pó. E o que não queimou, ficou marcado para sempre\r\n\r\nEssa não foi só mais uma “temporada de incêndios na Califórnia”. Foi um grito de alerta global. O clima está mudando — e rápido. A seca que preparou o terreno para essa catástrofe não foi natural: foi consequência direta de anos de desequilíbrio ambiental, emissões de carbono e omissão política.\r\n\r\nMais de 2.000 casas foram destruídas só em Pacific Palisades. Em Malibu, a destruição foi generalizada. Ao todo, 12 vidas se perderam. E a natureza, que levou séculos para se formar, virou fumaça em poucos dias.', 'uma-estrutura-queima-enquanto-o-incendio-palisades-queima-em-malibu-durante-uma-tempestade-de-vento-no-lado-oeste-de-los-angeles-california-eua-8-de-janeiro-de-2025-1736595083574_v2_450x337.jpg', 'aprovado', '2025-04-24 17:41:30'),
(91, 13, 4, 'A Califórnia em Chamas', 'Incêndios Devastam Malibu e Pacific Palisades', 'Geografia', '_Causas e consequências:\r\n\r\nOs especialistas apontam que a mudança climática tem papel central no agravamento desses eventos. A seca histórica na região e as temperaturas acima da média criaram o ambiente ideal para incêndios se espalharem rapidamente. Ventos fortes de até 80 km/h empurraram o fogo em direção ao litoral, dificultando a ação dos bombeiros.\r\nO governador Gavin Newsom declarou estado de emergência e reforçou a presença da Guarda Nacional na região. Brigadas de combate ao fogo foram enviadas de outros estados e até de fora do país, como México e Canadá.', 'have-few-homes-escaped-palisades-fire-devastation-in-malibu-heres-the-truth.webp', 'aprovado', '2025-04-24 17:41:30'),
(92, 14, 4, 'Onda de calor histórica entre Fevereiro e Março de 2025', 'Brasil enfrenta temperaturas extremas', 'Geografia', 'Sensação térmica ultrapassou os 60°C em algumas regiões; especialistas alertam para efeitos das mudanças climáticas e desigualdade social\r\n\r\nBRASIL – Nos meses de fevereiro e março de 2025, o país enfrentou uma das mais intensas ondas de calor já registradas. Com temperaturas entre 5°C e 7°C acima da média histórica, cidades de todas as regiões — especialmente do Centro-Sul — bateram recordes e enfrentaram situações críticas de saúde pública, sobrecarga energética e impacto ambiental.\r\n\r\nA onda de calor, a quinta somente em 2025, atingiu estados como São Paulo, Rio de Janeiro, Minas Gerais, Paraná, Goiás, Mato Grosso do Sul e o Distrito Federal, afetando milhões de pessoas. Em várias localidades, os termômetros superaram 40°C, em pleno verão.', 'termometro-registra-temperatura-de-37c-no-centro-da-cidade-de-sao-paulo-sp-nesta-terca-feira-10-1568163229340_v2_900x506.jpg.webp', 'aprovado', '2025-04-24 18:05:27'),
(93, 14, 4, 'Onda de calor histórica entre Fevereiro e Março de 2025', 'Brasil enfrenta temperaturas extremas', 'Geografia', '🌡️ Recordes e sensação térmica extrema\r\nNo Rio de Janeiro, o bairro de Guaratiba registrou a maior temperatura da década: 44°C, com sensação térmica que variou entre 50°C e 70°C, segundo o Instituto Nacional de Meteorologia (Inmet). A umidade alta e a falta de ventilação transformaram a cidade em um forno, sem trégua nem mesmo durante as madrugadas.\r\n\r\nOutras 44 cidades brasileiras também enfrentaram noites com temperaturas superiores a 30°C, o que comprometeu o sono e a saúde da população, principalmente idosos, crianças e pessoas com doenças respiratórias.\r\n\r\nNas favelas e periferias urbanas, como o Complexo da Maré, no Rio, a situação foi ainda mais crítica. Construções sem isolamento térmico, ruas sem arborização e alta densidade populacional criaram \"ilhas de calor\" com sensações térmicas superiores a 60°C. Organizações de direitos humanos alertam para a dimensão racial e social do problema, já que a maioria dos moradores dessas áreas é negra e sem acesso a infraestrutura adequada — um reflexo direto do que especialistas chamam de racismo ambiental.', NULL, 'aprovado', '2025-04-24 18:05:27'),
(94, 14, 4, 'Onda de calor histórica entre Fevereiro e Março de 2025', 'Brasil enfrenta temperaturas extremas', 'Geografia', 'Medidas emergenciais e desafios\r\nA prefeitura do Rio de Janeiro e outros municípios ativaram protocolos de emergência, incluindo postos de resfriamento, distribuição de água e campanhas educativas sobre os riscos do calor extremo. No entanto, ambientalistas alertam que essas ações são paliativas e insuficientes diante da frequência crescente das ondas de calor.\r\n\r\n“O Brasil precisa investir em políticas públicas de adaptação climática, principalmente nas áreas mais vulneráveis. Essa crise é um aviso claro do que está por vir”, afirmou a climatologista Paula Santos, da UFRJ.\r\n\r\n\r\n_Mudança climática como pano de fundo\r\n\r\nA causa da onda de calor está associada a um bloqueio atmosférico — um sistema de alta pressão que impede a chegada de frentes frias —, agravado pelo fenômeno El Niño e, principalmente, pelas mudanças climáticas globais.\r\n\r\nOs cientistas alertam: essas ondas de calor serão cada vez mais frequentes, longas e intensas. E o Brasil, como país tropical e desigual, está entre os mais vulneráveis.', 'mapa.webp', 'aprovado', '2025-04-24 18:05:27'),
(95, 15, 1, 'Explorando o Ciclo Trigonométrico', 'Entenda como o ciclo trigonométrico forma a espinha dorsal de várias aplicações na matemática e na física', 'Matemática', 'O ciclo trigonométrico é uma ferramenta essencial para compreender funções periódicas e o comportamento de ondas em diversas áreas do conhecimento. Ele representa a trajetória circular que descreve o movimento das funções seno, cosseno e tangente ao longo de um círculo unitário. Essa abordagem facilita a visualização e a resolução de problemas envolvendo ângulos e períodos em diversas disciplinas, como física, engenharia e até mesmo música.\r\n\r\nAo estudar o ciclo trigonométrico, começamos a perceber a importância de entender o movimento de uma partícula ao longo de um círculo, o que tem implicações diretas em fenômenos naturais, como ondas sonoras, luminosidade e o movimento dos planetas.\r\n\r\nEntre as principais funções que surgem a partir desse ciclo estão:\r\n\r\nSeno (sen): Representa a altura de um ponto ao longo do círculo unitário.\r\n\r\nCosseno (cos): Indica a distância horizontal de um ponto no círculo unitário.\r\n\r\nTangente (tan): Refere-se à razão entre o seno e o cosseno em um ponto específico.\r\n\r\nO ciclo trigonométrico também é crucial na análise de gráficos de funções periódicas, permitindo que se compreenda a amplitude, a frequência e o deslocamento dessas funções.', '2024_12_12-8dbb65643395af92fa535002568fffc6.png', 'aprovado', '2025-04-24 18:30:48'),
(96, 16, 4, 'Morre o Papa Francisco aos 88 anos:', ' Legado de vida dedicado à paz, humildade e compaixão', 'Língua Portuguesa', '_Vaticano, 21 de abril de 2025 \r\nO Papa Francisco faleceu nesta segunda-feira de Páscoa, 21 de abril de 2025, aos 88 anos, em sua residência na Domus Sanctae Marthae, na Cidade do Vaticano. A causa da morte foi um acidente vascular cerebral (AVC) seguido de insuficiência cardíaca, conforme confirmado pelo Vaticano.\r\nSegundo o médico pessoal do Papa, Dr. Sergio Alferi, Francisco estava em coma profundo quando ele chegou à residência às 5h30 da manhã. Apesar de estar com os olhos abertos, não havia resposta a estímulos. Alferi determinou que seria arriscado transportá-lo ao hospital, e ele faleceu pouco depois, acompanhado por sua equipe médica e em oração com o Cardeal Pietro Parolin.', '874a3224a91a3f963afd79026805201d.avif', 'aprovado', '2025-04-24 18:37:34'),
(97, 16, 4, 'Morre o Papa Francisco aos 88 anos:', ' Legado de vida dedicado à paz, humildade e compaixão', 'Língua Portuguesa', 'O corpo do Papa Francisco está sendo velado na Basílica de São Pedro, onde fiéis de todo o mundo têm prestado suas homenagens. O funeral será realizado no sábado, 26 de abril, na Basílica de São Pedro, com a presença de líderes religiosos e políticos internacionais .​\r\n\r\nO Papa Francisco deixa um legado de amor, compaixão e serviço aos outros. Sua vida e ensinamentos continuarão a inspirar gerações a viver com humildade e a trabalhar pela paz e justiça no mundo.', 'charge1443434304.jpg', 'aprovado', '2025-04-24 18:37:34'),
(98, 16, 4, 'Morre o Papa Francisco aos 88 anos:', ' Legado de vida dedicado à paz, humildade e compaixão', 'Língua Portuguesa', 'Especialistas em comportamento digital observam que o fenômeno não é novo, mas revela algo preocupante: a dificuldade crescente das pessoas em lidar com a morte de figuras públicas com empatia. “Vivemos a era da polarização emocional. Até um líder espiritual como o Papa vira alvo de zombarias”, comenta a socióloga Mariana Xavier, da PUC-SP.\r\n\r\nEla destaca que o anonimato e o distanciamento digital criam um terreno fértil para a desumanização. “As redes sociais têm dado espaço para uma crueldade disfarçada de ‘sinceridade’ ou ‘opinião pessoal’. Mas estamos falando da morte de um ser humano.”\r\n\r\n❤️ Um legado que resiste\r\nApesar das reações de desrespeito, a maioria das homenagens foram sinceras e emocionadas. Milhares de fiéis se dirigiram ao Vaticano para prestar suas últimas reverências. Líderes muçulmanos, judeus, budistas e hindus enviaram mensagens reconhecendo a contribuição de Francisco para o diálogo inter-religioso.\r\n\r\n“A história julgará o Papa Francisco não por seus críticos barulhentos, mas pelo amor silencioso que ele semeou ao redor do mundo”, disse o Cardeal Tagle, um dos nomes cotados para sucedê-lo.', 'Papa.jpg', 'aprovado', '2025-04-24 18:37:34'),
(99, 17, 2, 'Chuvas Intensificam Alerta em Diversos Estados do Brasil', 'Defesa Civil emite comunicados para regiões do Sul e Sudeste após fortes temporais', 'Língua Portuguesa', 'As fortes chuvas que atingem várias regiões do Brasil nesta semana já provocaram alagamentos, deslizamentos de terra e interrupções no fornecimento de energia em diversos municípios. Os estados mais afetados até o momento são Rio Grande do Sul, Santa Catarina, São Paulo e Rio de Janeiro.\r\nSegundo a Defesa Civil, o volume de chuva registrado em algumas cidades ultrapassou a média esperada para todo o mês de abril, o que elevou o risco de tragédias. Em Porto Alegre, por exemplo, bairros inteiros foram tomados pela água, obrigando famílias a deixarem suas casas às pressas.\r\n', 'alagamento_1.jpg', 'aprovado', '2025-04-25 13:18:50'),
(100, 17, 2, 'Chuvas Intensificam Alerta em Diversos Estados do Brasil', 'Defesa Civil emite comunicados para regiões do Sul e Sudeste após fortes temporais', 'Língua Portuguesa', 'Além dos transtornos urbanos, há preocupação com áreas rurais, onde lavouras foram inundadas, comprometendo colheitas e prejudicando pequenos agricultores. O governo federal já estuda medidas emergenciais de apoio às populações atingidas.\r\nAs autoridades recomendam que a população evite áreas de risco, siga as orientações da Defesa Civil e mantenha atenção aos alertas meteorológicos.\r\n', 'alagamento_2.jpg', 'aprovado', '2025-04-25 13:18:50'),
(101, 18, 3, 'Eu e o Arthur ', 'Arthur e eu ', 'Educação Física', 'Sjjdjdndndndnjsjsjjdjd', NULL, 'aprovado', '2025-04-25 13:30:25'),
(102, 19, 3, 'A gente e eu ', 'Eu e a gente ', 'História', 'Estamos testando a incrível revista do maninho erichi, de banho tomado.', NULL, 'aprovado', '2025-04-25 13:35:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(140) NOT NULL,
  `senha` varchar(40) NOT NULL,
  `id_permissoes_usuario` int(3) NOT NULL,
  `perfil_foto` varchar(225) NOT NULL,
  `bio` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `id_permissoes_usuario`, `perfil_foto`, `bio`) VALUES
(1, 'PROFESSOR', 'professor@gmail.com', '123', 3, 'uploads/680b8c7b9f8c9_foto_perfil.jpg', 'Eu amo o sesi'),
(2, 'ALUNO', 'aluno@gmail.com', '123', 2, '', ''),
(3, 'ADM', 'adm@gmail.com', '123', 4, '', ''),
(4, 'Jeniffer Colombo Hoffer', 'jeniffer@gmail.com', '474596', 4, 'uploads/6809164091ac2_Captura de tela 2025-04-16 142543.png', 'Sou aluna do 3°ano, amo ler livros.'),
(5, 'Breno Monteiro', 'breno@gmail.com', 'breno123', 3, 'uploads/68092923e7644_Captura de tela 2025-04-23 081357.png', 'eu amo o sesi'),
(6, 'Andre', 'andre@gmail.com', 'andre123', 3, '', ''),
(7, 'João Pedro', 'jpbentosperandio@gmail.com', '478142', 2, 'uploads/680a758e98fc5_6024b2f41652c25270c59c8770338cef.jpg', 'Amo o sese');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avisos`
--
ALTER TABLE `avisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_aviso` (`id_usuario_aviso`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_comentario` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Índices de tabela `likes`
--
ALTER TABLE `likes`
  ADD KEY `id_post_like` (`id_post_like`),
  ADD KEY `id_usuario_like` (`id_usuario_like`);

--
-- Índices de tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_solicitacoes`),
  ADD KEY `fk_usuario_solicitacao` (`id_usuario_solicitacoes`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_permissoes` (`id_permissoes_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avisos`
--
ALTER TABLE `avisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id_solicitacoes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avisos`
--
ALTER TABLE `avisos`
  ADD CONSTRAINT `avisos_ibfk_1` FOREIGN KEY (`id_usuario_aviso`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id_solicitacoes`),
  ADD CONSTRAINT `usuario_comentario` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_post_like`) REFERENCES `posts` (`id_solicitacoes`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_usuario_like`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_usuario_solicitacao` FOREIGN KEY (`id_usuario_solicitacoes`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuario_permissoes` FOREIGN KEY (`id_permissoes_usuario`) REFERENCES `permissoes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
