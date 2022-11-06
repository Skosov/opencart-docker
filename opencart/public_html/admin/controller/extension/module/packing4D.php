<?php
class ControllerExtensionModulePacking4D extends Controller {
	public function index() {  
		$this->load->language('extension/module/packing4D');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $boxes = $this->request->post['packing_boxes'] ?? "";

            if (!empty($boxes)) {
                $this->db->query( "TRUNCATE TABLE " . DB_PREFIX . "packing_boxes");
                foreach ($boxes as $box) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "packing_boxes
                    SET reference = '".$box['reference']."',
                    outerWidth = '" .$box['outerWidth'].  "',
                    outerLength = '" .$box['outerLength']. "',
                    outerDepth = '" .$box['outerDepth']. "',
                    emptyWeight = '" .$box['emptyWeight']. "',
                    innerWidth = '" .$box['innerWidth']. "',
                    innerLength = '" .$box['innerLength']. "',
                    innerDepth = '" .$box['innerDepth']. "',
                    maxWeight = '" .$box['maxWeight']. "' ,
                    price = '" .$box['price']. "',
                    shipping = '".json_encode($box['shipping'] ?? '')."'
                    ") ;
                     }
            }


			$this->session->data['success'] = $this->language->get('text_success');
						
			// $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));

        }

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "packing_boxes`" ) ;
        if ($query->rows) {
            $data['boxes'] = $query->rows;
            foreach ($data['boxes'] as &$box) {
                $box['shipping'] = json_decode($box['shipping'], true);
            }
            file_put_contents(DIR_LOGS.'boxes.txt', print_r($data['boxes'],1), FILE_APPEND);
        }


        $data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');

        //Boxes
        $data['entry_reference'] = $this->language->get('entry_reference');
        $data['entry_outerWidth'] = $this->language->get('entry_outerWidth');
        $data['entry_outerLength'] = $this->language->get('entry_outerLength');
        $data['entry_outerDepth'] = $this->language->get('entry_outerDepth');
        $data['entry_emptyWeight'] = $this->language->get('entry_emptyWeight');
        $data['entry_innerWidth'] = $this->language->get('entry_innerWidth');
        $data['entry_innerLength'] = $this->language->get('entry_innerLength');
        $data['entry_innerDepth'] = $this->language->get('entry_innerDepth');
        $data['entry_maxWeight'] = $this->language->get('entry_maxWeight');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_shipping_methods'] = $this->language->get('entry_shipping_methods');

        $data['edit_action'] = $this->language->get('edit_action');
        $data['button_option_value_add'] = $this->language->get('button_option_value_add');
        $data['text_supported_shipping'] = $this->language->get('text_supported_shipping');


  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href' 		=> $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
   		);

   		$data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/packing4D', 'user_token=' . $this->session->data['user_token'], true)
   		);

		$data['action'] = $this->url->link('extension/module/packing4D', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->post['shipping_omniva_reference'])) {
            $data['shipping_omniva_reference'] = $this->request->post['shipping_omniva_reference'];
        } else if ($this->config->get('shipping_omniva_reference')) {
            $data['shipping_omniva_reference'] = $this->config->get('shipping_omniva_reference');
        } else {
            $data['shipping_omniva_reference'] = '';
        }

        if (isset($this->request->post['shipping_omniva_outerWidth'])) {
            $data['shipping_omniva_outerWidth'] = $this->request->post['shipping_omniva_outerWidth'];
        } else if ($this->config->get('shipping_omniva_api_url')) {
            $data['shipping_omniva_outerWidth'] = $this->config->get('shipping_omniva_outerWidth');
        } else {
            $data['shipping_omniva_outerWidth'] = '';
        }

        if (isset($this->request->post['shipping_omniva_outerLength'])) {
            $data['shipping_omniva_outerLength'] = $this->request->post['shipping_omniva_outerLength'];
        } else if ($this->config->get('shipping_omniva_api_url')) {
            $data['shipping_omniva_outerLength'] = $this->config->get('shipping_omniva_outerLength');
        } else {
            $data['shipping_omniva_outerLength'] = '';
        }

        if (isset($this->request->post['shipping_omniva_outerDepth'])) {
            $data['shipping_omniva_outerDepth'] = $this->request->post['shipping_omniva_outerDepth'];
        } else if ($this->config->get('shipping_omniva_api_url')) {
            $data['shipping_omniva_outerDepth'] = $this->config->get('shipping_omniva_outerDepth');
        } else {
            $data['shipping_omniva_outerDepth'] = '';
        }

        if (isset($this->request->post['shipping_omniva_emptyWeight'])) {
            $data['shipping_omniva_emptyWeight'] = $this->request->post['shipping_omniva_emptyWeight'];
        } else if ($this->config->get('shipping_omniva_emptyWeight')) {
            $data['shipping_omniva_emptyWeight'] = $this->config->get('shipping_omniva_emptyWeight');
        } else {
            $data['shipping_omniva_emptyWeight'] = '';
        }

        if (isset($this->request->post['shipping_omniva_innerWidth'])) {
            $data['shipping_omniva_innerWidth'] = $this->request->post['shipping_omniva_innerWidth'];
        } else if ($this->config->get('shipping_omniva_innerWidth')) {
            $data['shipping_omniva_innerWidth'] = $this->config->get('shipping_omniva_innerWidth');
        } else {
            $data['shipping_omniva_innerWidth'] = '';
        }

        if (isset($this->request->post['shipping_omniva_innerLength'])) {
            $data['shipping_omniva_innerLength'] = $this->request->post['shipping_omniva_innerLength'];
        } else if ($this->config->get('shipping_omniva_innerLength')) {
            $data['shipping_omniva_innerLength'] = $this->config->get('shipping_omniva_innerLength');
        } else {
            $data['shipping_omniva_innerLength'] = '';
        }

        if (isset($this->request->post['shipping_omniva_innerDepth'])) {
            $data['shipping_omniva_innerDepth'] = $this->request->post['shipping_omniva_innerDepth'];
        } else if ($this->config->get('shipping_omniva_innerDepth')) {
            $data['shipping_omniva_innerDepth'] = $this->config->get('shipping_omniva_innerDepth');
        } else {
            $data['shipping_omniva_innerDepth'] = '';
        }

        if (isset($this->request->post['shipping_omniva_maxWeight'])) {
            $data['shipping_omniva_maxWeight'] = $this->request->post['shipping_omniva_maxWeight'];
        } else if ($this->config->get('shipping_omniva_maxWeight')) {
            $data['shipping_omniva_maxWeight'] = $this->config->get('shipping_omniva_maxWeight');
        } else {
            $data['shipping_omniva_maxWeight'] = '';
        }

        if (isset($this->request->post['shipping_omniva_price'])) {
            $data['shipping_omniva_price'] = $this->request->post['shipping_omniva_price'];
        } else if ($this->config->get('shipping_omniva_price')) {
            $data['shipping_omniva_price'] = $this->config->get('shipping_omniva_price');
        } else {
            $data['shipping_omniva_price'] = '';
        }
			
		$this->load->model('localisation/length_class');

		$length_class_data = $this->model_localisation_length_class->getLengthClass($this->config->get('config_length_class_id'));
			
		$data['default_length_class'] = $length_class_data['unit'];

		$this->load->model('localisation/weight_class');

		$weight_class_data = $this->model_localisation_weight_class->getWeightClass($this->config->get('config_weight_class_id'));
			
		$data['default_weight_class'] = $weight_class_data['unit'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        $this->load->model('setting/extension');

        $data['supported_modules'] = array(
            // поменять на омниву
            0 => array(
                'name' => 'omniva',
                'link' => 'https://partneris.net/omniva-shipping-extension-for-opencart-version-3-x'
            ),
            // поменять на дпд
            /* 1 => array(
                'name' => 'pickup',
                'link' => 'https://partneris.net/omniva-shipping-extension-for-opencart-version-3-x'
            )*/
        );

        $extensions = array();
        foreach ($data['supported_modules'] as $module) {
            $extensions[] = $module['name'];
        }

        $data['shipping_methods'] = array();

        $files = glob(DIR_APPLICATION . 'controller/extension/shipping/*.php');

        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                $this->load->language('extension/shipping/' . $extension, 'extension');

                if ($this->config->get('shipping_' . $extension . '_status') && in_array($extension, $extensions)) {
                    $data['shipping_methods'][] = array(
                        'name'       => $this->language->get('extension')->get('heading_title'),
                        'code' => $extension,
                    );
                }
            }
        }

		$this->response->setOutput($this->load->view('extension/module/packing4D', $data));
	}
    private function validate() {
        return true;
    }

    public function install() {

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "packing_boxes` (
		  `box_id` int(11) NOT NULL AUTO_INCREMENT,
		  `reference` text COLLATE utf8_bin NOT NULL,
		  `outerWidth` float(11) NOT NULL,
		  `outerLength` float(11) NOT NULL,
		  `outerDepth` float(11) NOT NULL,
		  `emptyWeight` float(11) NOT NULL,
		  `innerWidth` float(11) NOT NULL,
		  `innerLength` float(11) NOT NULL,
		  `innerDepth` float(11) NOT NULL,
		  `maxWeight` float(11) NOT NULL,
		  `shipping` text,
		  `price` float(11) NOT NULL,
		  PRIMARY KEY (`box_id`)
		)");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_packing_data` (
		  `order_packing_data_id` int(11) NOT NULL AUTO_INCREMENT,
		  `order_id` int(11) NOT NULL,
		  `packing_data` JSON NOT NULL,
		  PRIMARY KEY (`order_packing_data_id`)
		)");

	}
}
?>