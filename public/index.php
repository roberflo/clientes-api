<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
  // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
  // whitelist of safe domains
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
      header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}

//TODO: Go to models and use reference
class Db
{
  private $host = 'localhost';
  private $user = 'root';
  private $pass = '';
  private $dbname = 'mundopaquete';
    
    public function connect()
    {
        $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
        $conn = new PDO($conn_str, $this->user, $this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}

//TODO: Add reference to model DB
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);



//General API 
$app->get('/', function (Request $request, Response $response) {
   $response->getBody()->write('Hello World!');
   return $response;
});

//TODO: use reference Customer 
//Customers Actions 
$app->get('/customers', function (Request $request, Response $response) {
    $sql = "SELECT * FROM customers";
   
    try {
      $db = new Db();
      $conn = $db->connect();
      $stmt = $conn->query($sql);
      $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;
     
      $response->getBody()->write(json_encode($customers));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
   
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

$app->get('/customers/{id}',function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $data = $request->getParsedBody();
    
    $sql = "SELECT * FROM customers WHERE id = $id";
              
    try {
      $db = new Db();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      
      $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;
      
      $response->getBody()->write(json_encode($customers));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );

      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

$app->post('/customers', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $CustomerName = $data["CustomerName"];
    $Email = $data["Email"];
    $Phone = $data["Phone"];
    $Address = $data["Address"];
    $TaxId = $data["TaxId"];
    $Company = $data["Company"];
    $NIT = $data["NIT"];
    $DUI = $data["DUI"];

    $sql = "INSERT INTO customers (CustomerName, Email, Phone, Address, TaxId, Company, NIT, DUI) VALUES (:CustomerName, :Email, :Phone, :Address, :TaxId, :Company, :NIT, :DUI)";
   
    try {
      $db = new Db();
      $conn = $db->connect();
     
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':CustomerName', $CustomerName);
      $stmt->bindParam(':Email', $Email);
      $stmt->bindParam(':Phone', $Phone);
      $stmt->bindParam(':Address', $Address);
      $stmt->bindParam(':TaxId', $TaxId);
      $stmt->bindParam(':Company', $Company);
      $stmt->bindParam(':NIT', $NIT);
      $stmt->bindParam(':DUI', $DUI);
      
      $result = $stmt->execute();
   
      $db = null;
      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
   
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

$app->put('/customers/{id}',function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $data = $request->getParsedBody();
        $CustomerName = $data["CustomerName"];
        $Email = $data["Email"];
        $Phone = $data["Phone"];
        $Address = $data["Address"];
        $TaxId = $data["TaxId"];
        $Company = $data["Company"];
        $NIT = $data["NIT"];
        $DUI = $data["DUI"];

    $sql = "UPDATE customers SET
              CustomerName = :CustomerName,
              Email = :Email,
              Phone = :Phone,
              Address = :Address,
              TaxId = :TaxId,
              Company = :Company,
              NIT = :NIT,
              DUI = :DUI
    WHERE id = $id";

    try {
      $db = new Db();
      $conn = $db->connect();
      
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':CustomerName', $CustomerName);
      $stmt->bindParam(':Email', $Email);
      $stmt->bindParam(':Phone', $Phone);
      $stmt->bindParam(':Address', $Address);
      $stmt->bindParam(':TaxId', $TaxId);
      $stmt->bindParam(':Company', $Company);
      $stmt->bindParam(':NIT', $NIT);
      $stmt->bindParam(':DUI', $DUI);

      $result = $stmt->execute();

      $db = null;
      echo "Update successful! ";
      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );

      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

$app->delete('/customers/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
   
    $sql = "DELETE FROM customers WHERE id = $id";
   
    try {
      $db = new Db();
      $conn = $db->connect();
     
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
   
      $db = null;
      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
   
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

//** Services Actions **//
    //Get services
    //Get service by id
    //Add service 
    //Update service by id
    //Delete service by id

//** Company Actions **//
    //Get company
    //Get company by id
    //Add company 
    //Update company by id
    //Delete company by id

//** Invoice Actions **//
//Get All invoices
$app->get('/invoices', function (Request $request, Response $response) {
    $sql = "SELECT * FROM invoices ORDER BY id  DESC";
    
    try {
      $db = new Db();
      $conn = $db->connect();
      $stmt = $conn->query($sql);
      $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;
      
      $response->getBody()->write(json_encode($customers));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
    
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

//Get invoices by Id
$app->get('/invoices/{id}',function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $data = $request->getParsedBody();
    
    $sql = "SELECT
                id,
                CreatedAt,
                UpdatedAt,
                SUBSTRING(CustomerName, 1, 5) AS CustomerName,
                NIT,
                DUI,
                SUBSTRING(Address, 1, 12) AS Address,
                TaxId,
                SUBSTRING(AccountOf, 1, 10) AS AccountOf,
                ExcentSales,
                NonSubjectsSales,
                SubTotal,
                IVA,
                Total,
                Description,
                CustomerId,
                Status
            FROM 
                invoices 
            WHERE 
                Id = $id";
              
    try {
      $db = new Db();
      $conn = $db->connect();
    
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      
      $invoices = $stmt->fetchAll(PDO::FETCH_OBJ);
      
      
      //Get Items
      $sqlItems = "SELECT 
                      id,
                      InvoiceId,
                      ExcentSales,
                      NonSubjectsSales,
                      Price,
                      Quantity, 
                      SUBSTRING(Description, 1, 8) AS Description
                  FROM 
                      invoiceItems 
                  WHERE 
                    InvoiceId = $id";

      $stmt = $conn->prepare($sqlItems);
      $result = $stmt->execute();
      $items = $stmt->fetchAll(PDO::FETCH_OBJ);
      
      //Add Items
      $invoices[0]->InvoiceItems = $items;
      
      
      
      $db = null;
      $response->getBody()->write(json_encode($invoices));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
    
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

//Get customer invoices
$app->get('/customers/{id}/invoices',function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $data = $request->getParsedBody();

    $sql = "SELECT * FROM invoices WHERE CustomerId = $id";
            
    try {
      $db = new Db();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      
      $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;
      
      $response->getBody()->write(json_encode($customers));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );

      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

//Add invoice to customer
//Invoice Header 
// Add company Data
// Add customer Data
// Add subtotal
// Add tax if needed
$app->post('/invoices', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();

    $CustomerName = $data["CustomerName"];
    $Address = $data["Address"];
    $TaxId = $data["TaxId"];
    $AccountOf = $data["AccountOf"];
    $ExcentSales = $data["ExcentSales"];
    $NonSubjectsSales = $data["NonSubjectsSales"];
    $SubTotal = $data["SubTotal"];
    $IVA = $data["IVA"];
    $Total = $data["Total"];
    $Description = $data["Description"];
    $CustomerId = $data["CustomerId"];
    $Status = $data["Status"];
    $DocumentType = $data["DocumentType"];

    $DtesId = 1;
    if ($DocumentType = "CreditoFiscal") { $DtesId = 2; }
   
    $sql = "INSERT INTO invoices 
          (
           CustomerName, Address, TaxId,
           AccountOf, ExcentSales, NonSubjectsSales,
           SubTotal, IVA, Total, Description, 
           CustomerId, Status, DtesId) 
           VALUES 
           (
            :CustomerName, :Address, :TaxId,
            :AccountOf, :ExcentSales, :NonSubjectsSales,
            :SubTotal, :IVA, :Total, :Description,
            :CustomerId, :Status, :DtesId)";

    try {
      $db = new Db();
      $conn = $db->connect();
     
      $stmt = $conn->prepare($sql);

      $stmt->bindParam(':CustomerName', $CustomerName );
      $stmt->bindParam(':Address', $Address );
      $stmt->bindParam(':TaxId', $TaxId );
      $stmt->bindParam(':AccountOf', $AccountOf );
      $stmt->bindParam(':ExcentSales', $ExcentSales );
      $stmt->bindParam(':NonSubjectsSales', $NonSubjectsSales );
      $stmt->bindParam(':SubTotal', $SubTotal );
      $stmt->bindParam(':IVA', $IVA );
      $stmt->bindParam(':Total', $Total );
      $stmt->bindParam(':Description', $Description );
      $stmt->bindParam(':CustomerId', $CustomerId );
      $stmt->bindParam(':Status', $Status );
      $stmt->bindParam(':DtesId', $DtesId );

      $result = $stmt->execute();
      $InvoiceId = $conn->lastInsertId();
   
      $db = null;
      $response->getBody()->write(json_encode($result));

      if ($result == true) {
          $invoiceItems = $data["InvoiceItems"];
          foreach($invoiceItems as $item) {
             
            
                $ExcentSales = $item["ExcentSales"];
                $NonSubjectsSales = $item["NonSubjectsSales"];
                $Price = $item["Price"];
                $Quantity = $item["Quantity"];
                $Description = $item["Description"];
            
                $sql = "INSERT INTO invoiceItems 
                            (InvoiceId, ExcentSales, NonSubjectsSales,
                            Price, Quantity, Description) 
                        VALUES 
                            (:InvoiceId, :ExcentSales, :NonSubjectsSales,
                            :Price, :Quantity, :Description)";
            
               
                  $db = new Db();
                  $conn = $db->connect();
                
                  $stmt = $conn->prepare($sql);
            
                  $stmt->bindParam(':InvoiceId', $InvoiceId );
                  $stmt->bindParam(':ExcentSales', $ExcentSales );
                  $stmt->bindParam(':NonSubjectsSales', $NonSubjectsSales );
                  $stmt->bindParam(':Price', $Price );
                  $stmt->bindParam(':Quantity', $Quantity );
                  $stmt->bindParam(':Description', $Description );
            
                  $result = $stmt->execute();
             
             
          };
      };
      $db = null;
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
   
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

//Delete or Cancell? - Invoice by Id
$app->delete('/invoices/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
  
    //softdelete? 
    $sql = "DELETE FROM invoices WHERE id = $id";
  
    try {
      $db = new Db();
      $conn = $db->connect();
    
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
  
      $db = null;
      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
  
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
 });

 $app->put('/invoices/{id}',function (Request $request, Response $response, array $args) {
      $id = $request->getAttribute('id');
      $data = $request->getParsedBody();
    
      $CustomerName = $data["CustomerName"];
      $Address = $data["Address"];
      $TaxId = $data["TaxId"];
      $AccountOf = $data["AccountOf"];
      $ExcentSales = $data["ExcentSales"];
      $NonSubjectsSales = $data["NonSubjectsSales"];
      $SubTotal = $data["SubTotal"];
      $IVA = $data["IVA"];
      $Total = $data["Total"];
      $Description = $data["Description"];
      $CustomerId = $data["CustomerId"];
      $Status = $data["Status"];
  
    $sql = "UPDATE invoices SET
              CustomerName = :CustomerName,
              Address = :Address,
              TaxId = :TaxId,
              AccountOf = :AccountOf,
              ExcentSales = :ExcentSales,
              NonSubjectsSales = :NonSubjectsSales,
              SubTotal = :SubTotal,
              IVA = :IVA,
              Total = :Total,
              Description = :Description,
              CustomerId = :CustomerId,
              Status = :Status
    WHERE id = $id";
  
    try {
      $db = new Db();
      $conn = $db->connect();
    
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':CustomerName', $CustomerName );
      $stmt->bindParam(':Address', $Address );
      $stmt->bindParam(':TaxId', $TaxId );
      $stmt->bindParam(':AccountOf', $AccountOf );
      $stmt->bindParam(':ExcentSales', $ExcentSales );
      $stmt->bindParam(':NonSubjectsSales', $NonSubjectsSales );
      $stmt->bindParam(':SubTotal', $SubTotal );
      $stmt->bindParam(':IVA', $IVA );
      $stmt->bindParam(':Total', $Total );
      $stmt->bindParam(':Description', $Description );
      $stmt->bindParam(':CustomerId', $CustomerId );
      $stmt->bindParam(':Status', $Status );
  
      $result = $stmt->execute();
  
      $db = null;
      echo "Update successful! ";
      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
  
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
 });


$app->post('/invoices/{id}/invoiceItems', function (Request $request, Response $response, array $args) {
    $InvoiceId = $request->getAttribute('id');  
    $data = $request->getParsedBody();

    $ExcentSales = $data["ExcentSales"];
    $NonSubjectsSales = $data["NonSubjectsSales"];
    $Price = $data["Price"];
    $Quantity = $data["Quantity"];
    $Description = $data["Description"];

    $sql = "INSERT INTO invoiceItems 
                (InvoiceId, ExcentSales, NonSubjectsSales,
                Price, Quantity, Description) 
            VALUES 
                (:InvoiceId, :ExcentSales, :NonSubjectsSales,
                :Price, :Quantity, :Description)";

    try {
      $db = new Db();
      $conn = $db->connect();
    
      $stmt = $conn->prepare($sql);

      $stmt->bindParam(':InvoiceId', $InvoiceId );
      $stmt->bindParam(':ExcentSales', $ExcentSales );
      $stmt->bindParam(':NonSubjectsSales', $NonSubjectsSales );
      $stmt->bindParam(':Price', $Price );
      $stmt->bindParam(':Quantity', $Quantity );
      $stmt->bindParam(':Description', $Description );

      $result = $stmt->execute();
  
      $db = null;
      $response->getBody()->write(json_encode($result));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
      $error = array(
        "message" => $e->getMessage()
      );
  
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

//Setting Actions
$app->get('/settings/{id}', function (Request $request, Response $response) {
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM settings WHERE id = $id";
 
  try {
    $db = new Db();
    $conn = $db->connect();
    $stmt = $conn->query($sql);
    $settings = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
   
    $response->getBody()->write(json_encode($settings));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(200);
  } catch (PDOException $e) {
    $error = array(
      "message" => $e->getMessage()
    );
 
    $response->getBody()->write(json_encode($error));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(500);
  }
});

$app->put('/settings/{id}',function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $data = $request->getParsedBody();
    $name = $data["name"];
    $value = $data["value"];

  $sql = "UPDATE settings SET
            name = :name,
            value = :value
  WHERE id = $id";

  try {
    $db = new Db();
    $conn = $db->connect();
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':value', $value);

    $result = $stmt->execute();

    $db = null;
    echo "Update successful! ";
    $response->getBody()->write(json_encode($result));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(200);
  } catch (PDOException $e) {
    $error = array(
      "message" => $e->getMessage()
    );

    $response->getBody()->write(json_encode($error));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(500);
  }
});

$app->run();
