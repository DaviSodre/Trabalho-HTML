<?php
    session_start();
    include_once('config.php');
    // print_r($_SESSION);
    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true))
    {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');
    }

    // Função para fazer logout
    function logout() {
        // Limpa as variáveis de sessão
        $_SESSION = array();

        // Destroi a sessão
        session_destroy();
    }

    // Verifica se o botão de logout foi clicado
    if(isset($_GET['logout'])) {
        logout();
        // Redireciona para a página de login após o logout
        header('Location: login.php');
        exit;
    }
    $logado = $_SESSION['email']; // mantém o email atual
    $nomeUsuario = ''; // inicializa a variável para armazenar o nome do usuário

    // Verifica se o email está definido na sessão
    if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Consulta o banco de dados para obter o nome do usuário associado ao email
    $sqlNomeUsuario = "SELECT usuario FROM usuarios WHERE email = '$email'";
    $resultadoNomeUsuario = $conexao->query($sqlNomeUsuario);

    // Verifica se a consulta retornou resultados
    if($resultadoNomeUsuario->num_rows > 0) {
        $row = $resultadoNomeUsuario->fetch_assoc();
        $nomeUsuario = $row['usuario']; // armazena o nome do usuário
    }
}

    // Atualiza a variável $logado para exibir o nome do usuário, se disponível
    if(!empty($nomeUsuario)) {
        $logado = $nomeUsuario;
}
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM usuarios WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
        $sql = "SELECT * FROM usuarios ORDER BY id DESC";
    }
    $result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Clima agora!</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
      rel="stylesheet"
    />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/styles.css" />
  </head>
  <style>
    .logout-button {
    background-color: red;
    color: white;
    border: 2px solid red;
    padding: 7px 20px;
    text-decoration: none;
    border-radius: 10px;
    position: absolute;
    margin-top: -550px;
    margin-right: -1250px;
    
    
    
    
}

.logout-button:hover {
    background-color: darkred;
    
}
  </style>
  <body>
  <a href="?logout=true" class="logout-button">Sair</a>
    <div class="container">
        
      <div class="form">
      
      <h3>Bem vindo <?php echo $logado; ?></h3>
        <h3>Confira o clima de uma cidade:</h3>
        
        <div class="form-input-container">
          <input
          type="text"
          placeholder="Digite o nome da cidade"
          id="city-input"
        />
        <button id="search">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        </div>
      </div>
      <div id="weather-data" class="hide">
        <h2><i class="fa-solid fa-location-dot"></i> <span id="city"></span> <img id="country" crossorigin="anonymous"></img></h2>
        <p id="temperature"><span></span>&deg;C</p>
        <div id="description-container">
          <p id="description"></p>
          <img id="weather-icon" src="" alt="Condições atuais">
        </div>
        <div id="details-container">
          <p id="umidity">
            <i class="fa-solid fa-droplet"></i> 
            <span></span>
          </p>
          <p id="wind">
            <i class="fa-solid fa-wind"></i>
            <span></span>
          </p>
        </div>
      </div>
      <div id="error-message" class="hide">
        <p>Não foi possível encontrar o clima de uma cidade com este nome.</p>
      </div>
      <div id="loader" class="hide">
        <i class="fa-solid fa-spinner"></i>
      </div>
      <div id="suggestions">
        <button id="viena">Viena</button>
        <button id="copenhague">Copenhague</button>
        <button id="zurique">Zurique</button>
        <button id="vancouver">Vancouver</button>
        <button id="genebra">Genebra</button>
        <button id="frankfurt">Frankfurt</button>
        <button id="osaka">Osaka</button>
        <button id="maceio">Maceió</button>
      </div>
      <br>
      
    </div>
    <script>
const apiKey = "e1770d669fd28c05cd483bde24ec27a2";
const apiCountryURL = "https://flagcdn.com/16x12/br.png";
const apiUnsplash = "https://source.unsplash.com/1600x900/?";

const cityInput = document.querySelector("#city-input");
const searchBtn = document.querySelector("#search");

const cityElement = document.querySelector("#city");
const tempElement = document.querySelector("#temperature span");
const descElement = document.querySelector("#description");
const weatherIconElement = document.querySelector("#weather-icon");
const countryElement = document.querySelector("#country");
const umidityElement = document.querySelector("#umidity span");
const windElement = document.querySelector("#wind span");

const weatherContainer = document.querySelector("#weather-data");

const errorMessageContainer = document.querySelector("#error-message");
const loader = document.querySelector("#loader");

const suggestionContainer = document.querySelector("#suggestions");
const suggestionButtons = document.querySelectorAll("#suggestions button");

// Loader
const toggleLoader = () => {
  loader.classList.toggle("hide");
};

const getWeatherData = async (city) => {
  toggleLoader();

  const apiWeatherURL = `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}&lang=pt_br`;

  const res = await fetch(apiWeatherURL);
  const data = await res.json();

  toggleLoader();

  return data;
};

// Tratamento de erro
const showErrorMessage = () => {
  errorMessageContainer.classList.remove("hide");
};

const hideInformation = () => {
  errorMessageContainer.classList.add("hide");
  weatherContainer.classList.add("hide");

  suggestionContainer.classList.add("hide");
};

const showWeatherData = async (city) => {
  hideInformation();

  const data = await getWeatherData(city);

  if (data.cod === "404") {
    showErrorMessage();
    return;
  }

  cityElement.innerText = data.name;
  tempElement.innerText = parseInt(data.main.temp);
  descElement.innerText = data.weather[0].description;
  weatherIconElement.setAttribute(
    "src",
    `http://openweathermap.org/img/wn/${data.weather[0].icon}.png`
  );
  countryElement.setAttribute("src", `https://flagcdn.com/16x12/${data.sys.country.toLowerCase()}.png`);
  umidityElement.innerText = `${data.main.humidity}%`;
  windElement.innerText = `${data.wind.speed}km/h`;

  // Change bg image
  document.body.style.backgroundImage = `url("${apiUnsplash + city}")`;

  weatherContainer.classList.remove("hide");
};

searchBtn.addEventListener("click", async (e) => {
  e.preventDefault();

  const city = cityInput.value;

  showWeatherData(city);
});

cityInput.addEventListener("keyup", (e) => {
  if (e.code === "Enter") {
    const city = e.target.value;

    showWeatherData(city);
  }
});

// Sugestões
suggestionButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    const city = btn.getAttribute("id");

    showWeatherData(city);
  });
});
    </script>
  </body>
</html>