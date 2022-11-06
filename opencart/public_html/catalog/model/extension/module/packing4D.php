<?php

class ModelExtensionModulePacking4D extends Model {
    public function getBoxes($name = null) {
        $cartProducts = $this->cart->getProducts();

        $prepareProductsForPacking4d = array();
        foreach ($cartProducts as $product) {
            $weight = $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));

            $length = $this->length->convert($product['length'], $product['length_class_id'], $this->config->get('config_length_class_id'));

            $width = $this->length->convert($product['width'], $product['length_class_id'], $this->config->get('config_length_class_id'));

            $height = $this->length->convert($product['height'], $product['length_class_id'], $this->config->get('config_length_class_id'));


            $prepareProductsForPacking4d[] = [
                'description' => $product['name'],
                'weight' => $weight,
                'length' => $length,
                'depth' => $height,
                'width' => $width,
                'keepFlat' => false,
                'quantity' => $product['quantity'],
            ];
        }

        $sql = "SELECT * FROM `" . DB_PREFIX . "packing_boxes`";

        if ($name) {
            $sql .= " WHERE `shipping` LIKE '%".$name."%'";
        }

        $boxesQuery = $this->db->query($sql) ;
        if ($boxesQuery->rows) {
            $boxes = $boxesQuery->rows;
            $json = [
                'boxes' => $boxes,
                'products' => $prepareProductsForPacking4d,
            ];


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://licenseportal.partneris.net/api/packing',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($json),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                )
            ));

            $packingData = json_decode(curl_exec($curl),true);
            if ($name) {
                $this->session->data['packing_data'][$name] = $packingData;
                curl_close($curl);
            }

            return $packingData;
        }
    }

    public function addPackedOrder($order_id) {
        if (isset($this->session->data['packing_data'])) {
            foreach ($this->session->data['packing_data'] as $key => $value) {
                $pos1 = stripos((string)$this->session->data['shipping_method']['code'], (string)$key);

                if ($pos1 !== false) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "order_packing_data` SET packing_data = '" . json_encode($value) . "', order_id = '".$order_id."'");
                }
            }
        }
    }
}
