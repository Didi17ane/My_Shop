<?php

include_once("database.php");
        $database = new Database();
        $db = $database->connect();
        try{
            
        $sq = "SELECT * FROM categories ORDER BY name";
        $que = $db->prepare($sq);
        $que->execute();
        $cat = $que->fetchAll(); ?>
        
        <script src="https://cdn.tailwindcss.com"></script>
        <select name="select" class="w-full border rounded px-4 py-2">
        <?php      
            foreach($cat as $c) { 
                echo "<option value=".$c['id'].">".$c['name']."</option>";   
            }
        }catch(Exception $e)
        {
            return $e->getMessage();
        }
        ?>
        </select>
<?php

       