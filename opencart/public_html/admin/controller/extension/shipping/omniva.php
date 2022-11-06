<?php
class ControllerExtensionShippingOmniva extends Controller {
	private $error = array(); 
	protected $labelsMix = 4;
	
	public function index() {  
		$data['SHIPPING_OMNIVA_VERSION'] = "4.1.0";
		$this->load->language('extension/shipping/omniva');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

		$tab_zones[0] = array(
			'code' => 'lv',
			'title' => $this->language->get('tab_latvia')
		);

		$tab_zones[1] = array(
			'code' => 'lt',
			'title' => $this->language->get('tab_lithuania')
		);

		$tab_zones[2] = array(
			'code' => 'ee',
			'title' => $this->language->get('tab_estonia')
		);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_omniva', $this->request->post);		

			$this->clearCache();
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_module_version'] = $this->language->get('text_module_version');
		$data['text_select_zone'] = $this->language->get('text_select_zone');
		$data['text_each_product'] = $this->language->get('text_each_product');
		$data['text_all_cart'] = $this->language->get('text_all_cart');
		$data['text_edit'] = $this->language->get('text_edit');

		$data['text_estonia'] = $this->language->get('text_estonia');
		$data['text_latvia'] = $this->language->get('text_latvia');
		$data['text_lithuania'] = $this->language->get('text_lithuania');
		
    	$data['entry_api_url'] = $this->language->get('entry_api_url');
    	$data['entry_username'] = $this->language->get('entry_username');
    	$data['entry_password'] = $this->language->get('entry_password');
    	$data['entry_label_type'] = $this->language->get('entry_label_type');
    	$data['entry_sender_name'] = $this->language->get('entry_sender_name');		
    	$data['entry_sender_address'] = $this->language->get('entry_sender_address');
    	$data['entry_sender_postcode'] = $this->language->get('entry_sender_postcode');
    	$data['entry_sender_city'] = $this->language->get('entry_sender_city');
    	$data['entry_sender_country_code'] = $this->language->get('entry_sender_country_code');
    	$data['entry_sender_phone'] = $this->language->get('entry_sender_phone');
    	$data['entry_cod_status'] = $this->language->get('entry_cod_status');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_bank'] = $this->language->get('entry_bank');
		$data['entry_services'] = $this->language->get('entry_services');
		$data['entry_services_parcel_terminal'] = $this->language->get('entry_services_parcel_terminal');
		$data['entry_services_courier'] = $this->language->get('entry_services_courier');

    	$data['entry_weight'] = $this->language->get('entry_weight');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_cost'] = $this->language->get('entry_cost');
		$data['entry_cost_courier'] = $this->language->get('entry_cost_courier');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_import_url'] = $this->language->get('entry_import_url');

		$data['help_dimension'] = $this->language->get('help_dimension');
		$data['help_weight'] = $this->language->get('help_weight');
		$data['help_rate'] = $this->language->get('help_rate');
		
		$data['button_clear_cache'] = $this->language->get('button_clear_cache');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['shipping_omniva_api_url'])) {
			$data['error_shipping_omniva_api_url'] = $this->error['shipping_omniva_api_url'];
		} else {
			$data['error_shipping_omniva_api_url'] = '';
		}

		if (isset($this->error['shipping_omniva_username'])) {
			$data['error_shipping_omniva_username'] = $this->error['shipping_omniva_username'];
		} else {
			$data['error_shipping_omniva_username'] = '';
		}

		if (isset($this->error['shipping_omniva_password'])) {
			$data['error_shipping_omniva_password'] = $this->error['shipping_omniva_password'];
		} else {
			$data['error_shipping_omniva_password'] = '';
		}

		if (isset($this->error['shipping_omniva_sender_name'])) {
			$data['error_shipping_omniva_sender_name'] = $this->error['shipping_omniva_sender_name'];
		} else {
			$data['error_shipping_omniva_sender_name'] = '';
		}

		if (isset($this->error['shipping_omniva_sender_address'])) {
			$data['error_shipping_omniva_sender_address'] = $this->error['shipping_omniva_sender_address'];
		} else {
			$data['error_shipping_omniva_sender_address'] = '';
		}

		if (isset($this->error['shipping_omniva_sender_postcode'])) {
			$data['error_shipping_omniva_sender_postcode'] = $this->error['shipping_omniva_sender_postcode'];
		} else {
			$data['error_shipping_omniva_sender_postcode'] = '';
		}

		if (isset($this->error['shipping_omniva_sender_city'])) {
			$data['error_shipping_omniva_sender_city'] = $this->error['shipping_omniva_sender_city'];
		} else {
			$data['error_shipping_omniva_sender_city'] = '';
		}

		if (isset($this->error['shipping_omniva_sender_country_code'])) {
			$data['error_shipping_omniva_sender_country_code'] = $this->error['shipping_omniva_sender_country_code'];
		} else {
			$data['error_shipping_omniva_sender_country_code'] = '';
		}

		if (isset($this->error['shipping_omniva_sender_phone'])) {
			$data['error_shipping_omniva_sender_phone'] = $this->error['shipping_omniva_sender_phone'];
		} else {
			$data['error_shipping_omniva_sender_phone'] = '';
		}

		if (isset($this->error['shipping_omniva_company'])) {
			$data['error_shipping_omniva_company'] = $this->error['shipping_omniva_company'];
		} else {
			$data['error_shipping_omniva_company'] = '';
		}

		if (isset($this->error['shipping_omniva_bank'])) {
			$data['error_shipping_omniva_bank'] = $this->error['shipping_omniva_bank'];
		} else {
			$data['error_shipping_omniva_bank'] = '';
		}

		if (isset($this->error['shipping_omniva_cod_status'])) {
			$data['error_shipping_omniva_cod_status'] = $this->error['shipping_omniva_cod_status'];
		} else {
			$data['error_shipping_omniva_cod_status'] = '';
		}

		if (isset($this->error['shipping_omniva_weight'])) {
			$data['error_shipping_omniva_weight'] = $this->error['shipping_omniva_weight'];
		} else {
			$data['error_shipping_omniva_weight'] = '';
		}

		if (isset($this->error['shipping_omniva_length'])) {
			$data['error_shipping_omniva_length'] = $this->error['shipping_omniva_length'];
		} else {
			$data['error_shipping_omniva_length'] = '';
		}
		
		if (isset($this->error['shipping_omniva_width'])) {
			$data['error_shipping_omniva_width'] = $this->error['shipping_omniva_width'];
		} else {
			$data['error_shipping_omniva_width'] = '';
		}

		if (isset($this->error['shipping_omniva_height'])) {
			$data['error_shipping_omniva_height'] = $this->error['shipping_omniva_height'];
		} else {
			$data['error_shipping_omniva_height'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href' 		=> $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/shipping/omniva', 'user_token=' . $this->session->data['user_token'], true)
   		);
		
		$data['clear_cache'] = $this->url->link('extension/shipping/omniva/clearCache', 'user_token=' . $this->session->data['user_token'], true);
		$data['action'] = $this->url->link('extension/shipping/omniva', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['tab_zones'] = $tab_zones;

		foreach ($tab_zones as $tab_zone) {
			if (isset($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_geo_zone_id'])) {
				$data['shipping_omniva_' . $tab_zone['code'] . '_geo_zone_id'] = $this->request->post['shipping_omniva_' . $tab_zone['code'] . '_geo_zone_id'];
			} else {
				$data['shipping_omniva_' . $tab_zone['code'] . '_geo_zone_id'] = $this->config->get('shipping_omniva_' . $tab_zone['code'] . '_geo_zone_id');
			}

			if (isset($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_tax_class_id'])) {
				$data['shipping_omniva_' . $tab_zone['code'] . '_tax_class_id'] = $this->request->post['shipping_omniva_' . $tab_zone['code'] . '_tax_class_id'];
			} else {
				$data['shipping_omniva_' . $tab_zone['code'] . '_tax_class_id'] = $this->config->get('shipping_omniva_' . $tab_zone['code'] . '_tax_class_id');
			}

			if (isset($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_cost'])) {
				$data['shipping_omniva_' . $tab_zone['code'] . '_cost'] = $this->request->post['shipping_omniva_' . $tab_zone['code'] . '_cost'];
			} else {
				$data['shipping_omniva_' . $tab_zone['code'] . '_cost'] = $this->config->get('shipping_omniva_' . $tab_zone['code'] . '_cost');
			}

			if (isset($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_free'])) {
				$data['shipping_omniva_' . $tab_zone['code'] . '_free'] = $this->request->post['shipping_omniva_' . $tab_zone['code'] . '_free'];
			} else {
				$data['shipping_omniva_' . $tab_zone['code'] . '_free'] = $this->config->get('shipping_omniva_' . $tab_zone['code'] . '_free');
			}
			
			if (isset($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'])) {
				$data['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'] = $this->request->post['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'];
			} else {
				$data['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'] = $this->config->get('shipping_omniva_' . $tab_zone['code'] . '_courier_cost');
			}

			if (isset($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_courier_free'])) {
				$data['shipping_omniva_' . $tab_zone['code'] . '_courier_free'] = $this->request->post['shipping_omniva_' . $tab_zone['code'] . '_courier_free'];
			} else {
				$data['shipping_omniva_' . $tab_zone['code'] . '_courier_free'] = $this->config->get('shipping_omniva_' . $tab_zone['code'] . '_courier_free');
			}
			
			if (isset($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_status'])) {
				$data['shipping_omniva_' . $tab_zone['code'] . '_status'] = $this->request->post['shipping_omniva_' . $tab_zone['code'] . '_status'];
			} else {
				$data['shipping_omniva_' . $tab_zone['code'] . '_status'] = $this->config->get('shipping_omniva_' . $tab_zone['code'] . '_status');
			}

			if (isset($this->error['shipping_omniva_' . $tab_zone['code'] . '_cost'])) {
				$data['error_shipping_omniva_' . $tab_zone['code'] . '_cost'] = $this->error['shipping_omniva_' . $tab_zone['code'] . '_cost'];
			} else {
				$data['error_shipping_omniva_' . $tab_zone['code'] . '_cost'] = '';
			}
			
			if (isset($this->error['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'])) {
				$data['error_shipping_omniva_' . $tab_zone['code'] . '_courier_cost'] = $this->error['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'];
			} else {
				$data['error_shipping_omniva_' . $tab_zone['code'] . '_courier_cost'] = '';
			}
		}
				
	    if (isset($this->request->post['shipping_omniva_api_url'])) {
	    	$data['shipping_omniva_api_url'] = $this->request->post['shipping_omniva_api_url'];
		} else if ($this->config->get('shipping_omniva_api_url')) {
			$data['shipping_omniva_api_url'] = $this->config->get('shipping_omniva_api_url');
		} else {
			$data['shipping_omniva_api_url'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_username'])) {
	    	$data['shipping_omniva_username'] = $this->request->post['shipping_omniva_username'];
		} else if ($this->config->get('shipping_omniva_username')) {
			$data['shipping_omniva_username'] = $this->config->get('shipping_omniva_username');
		} else {
			$data['shipping_omniva_username'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_password'])) {
	    	$data['shipping_omniva_password'] = $this->request->post['shipping_omniva_password'];
		} else if ($this->config->get('shipping_omniva_password')) {
			$data['shipping_omniva_password'] = $this->config->get('shipping_omniva_password');
		} else {
			$data['shipping_omniva_password'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_services_parcel_terminal'])) {
	    	$data['shipping_omniva_services_parcel_terminal'] = $this->request->post['shipping_omniva_services_parcel_terminal'];
		} else if ($this->config->get('shipping_omniva_services_parcel_terminal')) {
			$data['shipping_omniva_services_parcel_terminal'] = $this->config->get('shipping_omniva_services_parcel_terminal');
		} else {
			$data['shipping_omniva_services_parcel_terminal'] = 0;
	    }

	    if (isset($this->request->post['shipping_omniva_services_courier'])) {
	    	$data['shipping_omniva_services_courier'] = $this->request->post['shipping_omniva_services_courier'];
		} else if ($this->config->get('shipping_omniva_services_courier')) {
			$data['shipping_omniva_services_courier'] = $this->config->get('shipping_omniva_services_courier');
		} else {
			$data['shipping_omniva_services_courier'] = 0;
	    }

	    if (isset($this->request->post['shipping_omniva_label_type'])) {
	    	$data['shipping_omniva_label_type'] = $this->request->post['shipping_omniva_label_type'];
		} else if ($this->config->get('shipping_omniva_label_type')) {
			$data['shipping_omniva_label_type'] = $this->config->get('shipping_omniva_label_type');
		} else {
			$data['shipping_omniva_label_type'] = '1';
	    }

	    if (isset($this->request->post['shipping_omniva_sender_name'])) {
	    	$data['shipping_omniva_sender_name'] = $this->request->post['shipping_omniva_sender_name'];
		} else if ($this->config->get('shipping_omniva_sender_name')) {
			$data['shipping_omniva_sender_name'] = $this->config->get('shipping_omniva_sender_name');
		} else {
			$data['shipping_omniva_sender_name'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_sender_address'])) {
	    	$data['shipping_omniva_sender_address'] = $this->request->post['shipping_omniva_sender_address'];
		} else if ($this->config->get('shipping_omniva_sender_address')) {
			$data['shipping_omniva_sender_address'] = $this->config->get('shipping_omniva_sender_address');
		} else {
			$data['shipping_omniva_sender_address'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_sender_postcode'])) {
	    	$data['shipping_omniva_sender_postcode'] = $this->request->post['shipping_omniva_sender_postcode'];
		} else if ($this->config->get('shipping_omniva_sender_postcode')) {
			$data['shipping_omniva_sender_postcode'] = $this->config->get('shipping_omniva_sender_postcode');
		} else {
			$data['shipping_omniva_sender_postcode'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_sender_city'])) {
	    	$data['shipping_omniva_sender_city'] = $this->request->post['shipping_omniva_sender_city'];
		} else if ($this->config->get('shipping_omniva_sender_city')) {
			$data['shipping_omniva_sender_city'] = $this->config->get('shipping_omniva_sender_city');
		} else {
			$data['shipping_omniva_sender_city'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_sender_country_code'])) {
	    	$data['shipping_omniva_sender_country_code'] = $this->request->post['shipping_omniva_sender_country_code'];
		} else if ($this->config->get('shipping_omniva_sender_country_code')) {
			$data['shipping_omniva_sender_country_code'] = $this->config->get('shipping_omniva_sender_country_code');
		} else {
			$data['shipping_omniva_sender_country_code'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_sender_phone'])) {
	    	$data['shipping_omniva_sender_phone'] = $this->request->post['shipping_omniva_sender_phone'];
		} else if ($this->config->get('shipping_omniva_sender_phone')) {
			$data['shipping_omniva_sender_phone'] = $this->config->get('shipping_omniva_sender_phone');
		} else {
			$data['shipping_omniva_sender_phone'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_company'])) {
	    	$data['shipping_omniva_company'] = $this->request->post['shipping_omniva_company'];
		} else if ($this->config->get('shipping_omniva_company')) {
			$data['shipping_omniva_company'] = $this->config->get('shipping_omniva_company');
		} else {
			$data['shipping_omniva_company'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_bank'])) {
	    	$data['shipping_omniva_bank'] = $this->request->post['shipping_omniva_bank'];
		} else if ($this->config->get('shipping_omniva_bank')) {
			$data['shipping_omniva_bank'] = $this->config->get('shipping_omniva_bank');
		} else {
			$data['shipping_omniva_bank'] = '';
	    }

	    if (isset($this->request->post['shipping_omniva_cod_status'])) {
	    	$data['shipping_omniva_cod_status'] = $this->request->post['shipping_omniva_cod_status'];
		} else if ($this->config->get('shipping_omniva_cod_status')) {
			$data['shipping_omniva_cod_status'] = $this->config->get('shipping_omniva_cod_status');
		} else {
			$data['shipping_omniva_cod_status'] = '0';
	    }

	    if (isset($this->request->post['shipping_omniva_weight'])) {
	    	$data['shipping_omniva_weight'] = $this->request->post['shipping_omniva_weight'];
		} else if ($this->config->get('shipping_omniva_weight')) {
			$data['shipping_omniva_weight'] = $this->config->get('shipping_omniva_weight');
		} else {
			$data['shipping_omniva_weight'] = '30';
	    }

	    if (isset($this->request->post['shipping_omniva_length'])) {
	    	$data['shipping_omniva_length'] = $this->request->post['shipping_omniva_length'];
		} else if ($this->config->get('shipping_omniva_length')) {
			$data['shipping_omniva_length'] = $this->config->get('shipping_omniva_length');
		} else {
			$data['shipping_omniva_length'] = '38';
	    }

		if (isset($this->request->post['shipping_omniva_width'])) {
	    	$data['shipping_omniva_width'] = $this->request->post['shipping_omniva_width'];
		} else if ($this->config->get('shipping_omniva_width')) {
			$data['shipping_omniva_width'] = $this->config->get('shipping_omniva_width');
		} else {
			$data['shipping_omniva_width'] = '41';
	    }

	    if (isset($this->request->post['shipping_omniva_height'])) {
	    	$data['shipping_omniva_height'] = $this->request->post['shipping_omniva_height'];
		} else if ($this->config->get('shipping_omniva_height')) {
			$data['shipping_omniva_height'] = $this->config->get('shipping_omniva_height');
		} else {
			$data['shipping_omniva_height'] = '64';
	    }

			if (isset($this->request->post['shipping_omniva_import_url'])) {
				$data['shipping_omniva_import_url'] = $this->request->post['shipping_omniva_import_url'];
			} else if ($this->config->get('shipping_omniva_import_url')) {
				$data['shipping_omniva_import_url'] = $this->config->get('shipping_omniva_import_url');
			} else {
				$data['shipping_omniva_import_url'] = 'https://www.omniva.lv/locations.json';
			}

		if (isset($this->request->post['shipping_omniva_status'])) {
			$data['shipping_omniva_status'] = $this->request->post['shipping_omniva_status'];
		} else {
			$data['shipping_omniva_status'] = $this->config->get('shipping_omniva_status');
		}
			
		if (isset($this->request->post['shipping_omniva_sort_order'])) {
			$data['shipping_omniva_sort_order'] = $this->request->post['shipping_omniva_sort_order'];
		} else {
			$data['shipping_omniva_sort_order'] = $this->config->get('shipping_omniva_sort_order');
		}				

		$this->load->model('localisation/tax_class');
			
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
			
		$this->load->model('localisation/length_class');

		$length_class_data = $this->model_localisation_length_class->getLengthClass($this->config->get('config_length_class_id'));
			
		$data['default_length_class'] = $length_class_data['unit'];

		$this->load->model('localisation/weight_class');

		$weight_class_data = $this->model_localisation_weight_class->getWeightClass($this->config->get('config_weight_class_id'));
			
		$data['default_weight_class'] = $weight_class_data['unit'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/omniva', $data));
	}
	
	private function validate() {
		$this->load->language('extension/shipping/omniva');
		
		if (!$this->user->hasPermission('modify', 'extension/shipping/omniva')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$tab_zones[0] = array(
			'code' => 'lv',
			'title' => $this->language->get('tab_latvia')
		);

		$tab_zones[1] = array(
			'code' => 'lt',
			'title' => $this->language->get('tab_lithuania')
		);

		$tab_zones[2] = array(
			'code' => 'ee',
			'title' => $this->language->get('tab_estonia')
		);

		foreach ($tab_zones as $tab_zone) {
			if (((!$this->request->post['shipping_omniva_' . $tab_zone['code'] . '_cost']) || (strpos($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_cost'], ':') === false) || (strpos($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_cost'], ':') == 0)) && ($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_status'] == '1') && (isset($this->request->post['shipping_omniva_services_parcel_terminal'])) && ($this->request->post['shipping_omniva_services_parcel_terminal'] == '1')) {
				$this->error['shipping_omniva_' . $tab_zone['code'] . '_cost'] = $this->language->get('error_omniva_cost');
				$this->error['warning'] = $this->language->get('error_warning');
			}

			if (((!$this->request->post['shipping_omniva_' . $tab_zone['code'] . '_courier_cost']) || (strpos($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'], ':') === false) || (strpos($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'], ':') == 0)) && ($this->request->post['shipping_omniva_' . $tab_zone['code'] . '_status'] == '1') && (isset($this->request->post['shipping_omniva_services_courier'])) && ($this->request->post['shipping_omniva_services_courier'] == '1')) {
				$this->error['shipping_omniva_' . $tab_zone['code'] . '_courier_cost'] = $this->language->get('error_omniva_cost');
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}

		if (!$this->request->post['shipping_omniva_api_url']) {
			$this->error['shipping_omniva_api_url'] = $this->language->get('error_omniva_api_url');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_username']) {
			$this->error['shipping_omniva_username'] = $this->language->get('error_omniva_username');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_password']) {
			$this->error['shipping_omniva_password'] = $this->language->get('error_omniva_password');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_sender_name']) {
			$this->error['shipping_omniva_sender_name'] = $this->language->get('error_omniva_sender_name');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_sender_address']) {
			$this->error['shipping_omniva_sender_address'] = $this->language->get('error_omniva_sender_address');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_sender_postcode']) {
			$this->error['shipping_omniva_sender_postcode'] = $this->language->get('error_omniva_sender_postcode');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_sender_city']) {
			$this->error['shipping_omniva_sender_city'] = $this->language->get('error_omniva_sender_city');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_sender_country_code']) {
			$this->error['shipping_omniva_sender_country_code'] = $this->language->get('error_omniva_sender_country_code');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_sender_phone']) {
			$this->error['shipping_omniva_sender_phone'] = $this->language->get('error_omniva_sender_phone');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if ($this->request->post['shipping_omniva_cod_status']) {
			if (!$this->request->post['shipping_omniva_company']) {
				$this->error['shipping_omniva_company'] = $this->language->get('error_omniva_company');
				$this->error['warning'] = $this->language->get('error_warning');
			}

			if (!$this->request->post['shipping_omniva_bank']) {
				$this->error['shipping_omniva_bank'] = $this->language->get('error_omniva_bank');
				$this->error['warning'] = $this->language->get('error_warning');
			}
		}

		if (!$this->request->post['shipping_omniva_weight']) {
			$this->error['shipping_omniva_weight'] = $this->language->get('error_omniva_weight');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_length']) {
			$this->error['shipping_omniva_length'] = $this->language->get('error_omniva_length');
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		if (!$this->request->post['shipping_omniva_width']) {
			$this->error['shipping_omniva_width'] = $this->language->get('error_omniva_width');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->request->post['shipping_omniva_height']) {
			$this->error['shipping_omniva_height'] = $this->language->get('error_omniva_height');
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return (!$this->error);
	}
	
	public function clearCache() {
		$this->load->language('extension/shipping/omniva');

		$tab_zones = array('lv','lt','ee');
		
		foreach ($tab_zones as $tab_zone) {
			$this->cache->delete('omniva_addresses_'.$tab_zone);
		}		

		$cabins = file_get_contents($this->config->get('shipping_omniva_import_url'));
		$cabins = json_decode($cabins, true);

		foreach ($tab_zones as $tab_zone) {
			$ps_addresses = array();
			$omniva_addresses = array();		

//			$i = 1;
			foreach ($cabins as $ps_zone_k => $ps_zone_v) {
				if ((strcasecmp($ps_zone_v['A0_NAME'], $tab_zone) == 0) && $tab_zone == 'ee' && $ps_zone_v['TYPE'] == '0') {
					$ps_addresses[$ps_zone_v['ZIP']] = $ps_zone_v['NAME'];
					if (isset($ps_zone_v['A5_NAME'])) $ps_addresses[$ps_zone_v['ZIP']] .= ' - ' . $ps_zone_v['A5_NAME'];
					if (isset($ps_zone_v['A7_NAME'])) $ps_addresses[$ps_zone_v['ZIP']] .= ' - ' . $ps_zone_v['A7_NAME'];
					$ps_addresses[$ps_zone_v['ZIP']] .= ' - Omniva';
//					$i++;
				} else if ((strcasecmp($ps_zone_v['A0_NAME'], $tab_zone) == 0) && $tab_zone != 'ee') {
					$ps_addresses[$ps_zone_v['ZIP']] = $ps_zone_v['NAME'];
					if (isset($ps_zone_v['A5_NAME'])) $ps_addresses[$ps_zone_v['ZIP']] .= ' - ' . $ps_zone_v['A5_NAME'];
					if (isset($ps_zone_v['A7_NAME'])) $ps_addresses[$ps_zone_v['ZIP']] .= ' - ' . $ps_zone_v['A7_NAME'];
					$ps_addresses[$ps_zone_v['ZIP']] .= ' - Omniva';
//					$i++;
				}
			}

//file_put_contents(DIR_LOGS.'svlog_omniva_ps_addresses.txt', print_r($ps_addresses,1), FILE_APPEND);
//			usort($ps_addresses, 'strnatcmp');
			asort($ps_addresses);
		
			foreach ($ps_addresses as $ps_addresses_key => $ps_addresses_value) {		
				$omniva_addresses['omniva.omniva_'.$ps_addresses_key] = $ps_addresses_value;
			}	

			$this->cache->set('omniva_addresses_'.$tab_zone.'.catalog', $omniva_addresses);						
		}
	
		$this->session->data['success'] = $this->language->get('text_success_clear_cache');
		
		$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));		
	}
	
    public function install() {
		/* create tables */
		$this->db->query('CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'order_omniva` (
			`order_omniva_id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL,
			`tracking_nr` varchar(256) NOT NULL,
			`labels_count` int(5) NOT NULL DEFAULT 1,
			`omniva_weight` decimal(15,4) NOT NULL,
			`cod_amount` decimal(15,4) NOT NULL DEFAULT 0.0000,
			PRIMARY KEY (`order_omniva_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8');

//		$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
	}

	public function label() {
		$label_status = false;

		if (isset($_GET['order_id'])) {
			$_POST['selected'] = array($_GET['order_id']);
		}
		if (isset($_GET['label_status'])) {
			$label_status = true;
		}

		$label_print_type = (int) $this->config->get('shipping_omniva_label_type');
		if (!in_array($label_print_type, [1, 2])) {
			$label_print_type = 1; // default is A4
		}

		if (isset($_POST['selected']) && count($_POST['selected'])) {
			$this->load->model('sale/order');

			require_once DIR_SYSTEM . 'library/omniva/tcpdf/tcpdf.php';
			require_once DIR_SYSTEM . 'library/omniva/fpdi/src/autoload.php';

			$errors = array();
			$pages = 0;
			
			$pdf = new \setasign\Fpdi\Tcpdf\Fpdi('P');
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			foreach ($_POST['selected'] as $order_id) {
				$order = $this->model_sale_order->getOrder($order_id);
				$order_omniva = $this->model_sale_order->getOrderOmniva($order_id);
				
				if (stripos($order['shipping_code'], 'omniva') !== false) {				
					//cicle in case for additional labels
					$count = intval($order_omniva['labels_count']);
					
	//				$fullWeight = $this->getOrderWeight($order['order_id']);
					$fullWeight = 1;
					if (isset($order_omniva['omniva_weight']) && $order_omniva['omniva_weight'] > 0) {
						$fullWeight = $order_omniva['omniva_weight'];
					}

					if ($fullWeight == null) {
						$fullWeight = 0;
					}

					$weight = $fullWeight / $count;

					if (stripos($order['shipping_code'], 'omniva.courier_') !== false) {
						/************ container for courier *********/
						$pack = $count;
						$order['id'] = $order_id;
						$labels = $order_id . '_' . $pack;
						if ($pack == 1) {
							$labels = $order_id . '_0';
						}

						//if printed
						$shortage = 0;
						$shortageLbl = '';
						for ($i = 0; $i < $count; $i++) {
							$searchFor = $order_id . '_' . $i;
							if (file_exists(DIR_DOWNLOAD . 'omniva_' . $searchFor . '.pdf') && $label_status) {
								unlink(DIR_DOWNLOAD . 'omniva_' . $searchFor . '.pdf');
							}

							if (!file_exists(DIR_DOWNLOAD . 'omniva_' . $searchFor . '.pdf') || $label_status) {
								$shortage += 1;
								if ($shortageLbl == '') {
									$shortageLbl = $i;
								}
							}
						}

						if ($shortage > 0 && is_integer($shortageLbl)) { //var_dump($shortageLbl);exit();
							$status = $this->get_tracking_number($order, $weight, $shortage, 'courier');
							if ($status['status']) {
								foreach ($status['barcodes'] as $barcode) {
									$labelPrint = $order_id . '_' . $shortageLbl;
									$this->getShipmentLabels($barcode, $labelPrint);
									if (isset($barcode) and $barcode == true) {
										$this->setOmnivaOrder($order_id, $barcode, $weight, $order_omniva['labels_count']);
									}
								}
							} else {
								$errors[] = $order_id . ' - ' . $status['msg'];
								continue;
							}
						}
						for ($ik = 0; $ik < $count; $ik++) {
							$labels = $order_id . '_' . $ik;
							$label_url = DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf';
							if (!file_exists(DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf')) {
								continue;
							}

							$pagecount = $pdf->setSourceFile($label_url);

							switch ($label_print_type) {
								case 1: // A4
									$newPG = array(0, 4, 8, 12, 16, 20, 24, 28, 32);

									if ($this->labelsMix >= 4) {
										$pdf->AddPage();
										$page = 1;
										$templateId = $pdf->importPage($page);
										$this->labelsMix = 0;
									}
									$tplidx = $pdf->ImportPage(1);
									if ($this->labelsMix == 0) {
										$pdf->useTemplate($tplidx, 5, 15, 94.5, 108, false);
									} else if ($this->labelsMix == 1) {
										$pdf->useTemplate($tplidx, 110, 15, 94.5, 108, false);
									} else if ($this->labelsMix == 2) {
										$pdf->useTemplate($tplidx, 5, 140, 94.5, 108, false);
									} else if ($this->labelsMix == 3) {
										$pdf->useTemplate($tplidx, 110, 140, 94.5, 108, false);
									} else {
										echo 'Problems with labels count, please, select one order!!!';
									}

									$pages++;

									$this->labelsMix++;

									break;

								case 2: // single
									for ($i = 1; $i <= $pagecount; $i++) {
										$tplidx = $pdf->ImportPage($i);
										$s = $pdf->getTemplatesize($tplidx);
										$pdf->AddPage('P', array($s['width'], $s['height']));
										$pdf->useTemplate($tplidx);

										$pages++;
									}
								default:
									# code...
									break;
							}
						}
					} else {
						/**************container for parcel terminals ************* */
						for ($count = 0; $count < intval($order_omniva['labels_count']); $count++) {
							$pack = $count;
							$labels = $order_id . '_' . $pack;
							if ($pack == 1) {
								$labels = $order_id;
							}

							$track_numer = true;
							if (file_exists(DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf')) {
								$track_numer = false;
							}

							if ($track_numer != false || $label_status) {
								$status = $this->get_tracking_number($order, $weight);

								if ($status['status']) {
									$track_numer = $status['barcodes'][0];
									if (file_exists(DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf')) {
										unlink(DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf');
									}
								} else {
									$errors[] = $order_id . ' - ' . $status['msg'];
									continue;
								}
							}

							$label_url = '';
							if (file_exists(DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf')) {
								$label_url = DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf';
							}

							if ($label_url == '' || $label_status) {
								$label_status = $this->getShipmentLabels($track_numer, $labels);
								if ($label_status['status']) {
									if (file_exists(DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf')) {
										$label_url = DIR_DOWNLOAD . 'omniva_' . $labels . '.pdf';
	//									$this->sendNotification($order_id, $track_numer);
									}
								} else {
									$errors[] = $order_id . ' - ' . $label_status['msg'];
								}
								if ($label_url == '') {
									continue;
								}
							}

							$pagecount = $pdf->setSourceFile($label_url);

							switch ($label_print_type) {
								case 1: // A4
									$newPG = array(0, 4, 8, 12, 16, 20, 24, 28, 32);
									if ($this->labelsMix >= 4) {
										$pdf->AddPage();
										$page = 1;
										$templateId = $pdf->importPage($page);
										$this->labelsMix = 0;
									}

									$tplidx = $pdf->ImportPage(1);
									if ($this->labelsMix == 0) {
										$pdf->useTemplate($tplidx, 5, 15, 94.5, 108, false);
									} else if ($this->labelsMix == 1) {
										$pdf->useTemplate($tplidx, 110, 15, 94.5, 108, false);
									} else if ($this->labelsMix == 2) {
										$pdf->useTemplate($tplidx, 5, 140, 94.5, 108, false);
									} else if ($this->labelsMix == 3) {
										$pdf->useTemplate($tplidx, 110, 140, 94.5, 108, false);
									} else {
										echo 'Problems with labels count, please, select one order!!!';
									}
									$pages++;
									$this->labelsMix++;
									break;
								case 2: // single
									for ($i = 1; $i <= $pagecount; $i++) {
										$tplidx = $pdf->ImportPage($i);
										$s = $pdf->getTemplatesize($tplidx);
										$pdf->AddPage('P', array($s['width'], $s['height']));
										$pdf->useTemplate($tplidx);

										$pages++;
									}
									break;

								default:
									# code...
									break;
							}

							if ($track_numer) {
								$this->setOmnivaOrder($order_id, $track_numer, $weight, $order_omniva['labels_count']);
							}
						}
					}
				}
			}
			if ($pages) {
//				$pdf->Output('Omniva_labels.pdf', 'D');
				$this->response->setOutput($pdf->Output('Omniva_labels.pdf', 'D'));		
			} else {
				echo implode('<br/>', $errors);
			}
		} else {
			echo "No orders selected";
			return 0;
		}
	}

	public function get_tracking_number($order, $weight = 1, $packs = 1, $sendType = 'parcel') {
		if (stripos($order['shipping_code'], 'omniva') === false) {
		  return array('error' => 'Not Omniva shipping method');
		}

		$terminal_id = 0;
		if (stripos($order['shipping_code'], 'omniva_') !== false) {
		  $terminal_id = str_ireplace('omniva.omniva_', '', $order['shipping_code']);
		}

		$send_method = '';
		if (stripos($order['shipping_code'], 'omniva.omniva_') !== false) {
		  $send_method = 'pt';
		}
		if (stripos($order['shipping_code'], 'omniva.courier_') !== false) {
		  $send_method = 'c';
		}

		$service = "";
		switch ($send_method) {
		  case 'c':
			$service = "PK";
			break;
		  case 'pt':
			$service = "PA";
			break;
		  default:
			$service = "";
			break;
		}

		$parcel_terminal = "";
		if ($send_method == "pt") {
		  $parcel_terminal = 'offloadPostcode="' . $terminal_id . '" ';
		}

		$additionalService = '';
		if ($service == "PA") {
		  $additionalService .= '<option code="ST" />';
		}

		if ($order['payment_code'] == 'cod') {
		  $additionalService .= '<option code="BP" />';
		  $order['payment_code'] = 'cod';
		}

		if ($additionalService) {
		  $additionalService = '<add_service>' . $additionalService . '</add_service>';
		}

//		$cod_amount = $order['total'];
		$cod_amount = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);

		$phones = '';
		if ($order['telephone']) {
		  $phones .= '<mobile>' . $order['telephone'] . '</mobile>';
		}

		$shop_country_iso = $order['shipping_iso_code_2'];
		$xmlRequest = '
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://service.core.epmx.application.eestipost.ee/xsd">
			   <soapenv:Header/>
			   <soapenv:Body>
				  <xsd:businessToClientMsgRequest>
					 <partner>' . $this->config->get('shipping_omniva_username') . '</partner>
					 <interchange msg_type="info11">
						<header file_id="' . \Date('YmdHms') . '" sender_cd="' . $this->config->get('shipping_omniva_username') . '" >
						</header>
						<item_list>
						  ';
		$assignCount = null;
		if ($packs > 1 and $sendType != 'parcel') {
			$assignCount = 'packetUnitIdentificator="' . $order['id'] . '"';
		}
		for ($i = 0; $i < $packs; $i++) :
		  $postCode = preg_match('/(LV-)?\d+/', $order['shipping_postcode'], $matches); //426r    <address postcode="'.$order['shipping_postcode'].'"
		  $postCode = $postCode ? $matches[0] : '';
		  $xmlRequest .= '
								   <item service="' . $service . '" ' . $assignCount . '>
									  ' . $additionalService . '
									  <measures weight="' . $weight . '" />
									  ' . $this->cod($order, ($order['payment_code'] == 'cod'), $cod_amount) . '
									  <receiverAddressee >
										 <person_name>' . $order['shipping_firstname'] . ' ' . $order['shipping_lastname'] . '</person_name>
										' . $phones . '
										 <address postcode="' . $postCode . '" ' . $parcel_terminal . ' deliverypoint="' . ($order['shipping_city'] ? $order['shipping_city'] : $order['shipping_zone']) . '" country="' . $order['shipping_iso_code_2'] . '" street="' . $order['shipping_address_1'] . '" />
									  </receiverAddressee>
									  <!--Optional:-->
									  <returnAddressee>
										 <person_name>' . $this->config->get('shipping_omniva_sender_name') . '</person_name>
										 <!--Optional:-->
										 <phone>' . $this->config->get('shipping_omniva_sender_phone') . '</phone>
										 <address postcode="' . $this->config->get('shipping_omniva_sender_postcode') . '" deliverypoint="' . $this->config->get('shipping_omniva_sender_city') . '" country="' . $this->config->get('shipping_omniva_sender_country_code') . '" street="' . $this->config->get('shipping_omniva_sender_address') . '" />

									  </returnAddressee>

								   </item>';
		endfor;
		$xmlRequest .= '
						</item_list>
					 </interchange>
				  </xsd:businessToClientMsgRequest>
			   </soapenv:Body>
			</soapenv:Envelope>';
	//file_put_contents(DIR_LOGS.'svlog_omniva_get_tracking_number1.txt', print_r($xmlRequest,1), FILE_APPEND);
		return $this->api_request($xmlRequest);
	}

	public function getShipmentLabels($barcodes, $order_id = null) {
		$errors = array();
		$barcodeXML = '';

		$xmlRequest = '
			<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://service.core.epmx.application.eestipost.ee/xsd">
			   <soapenv:Header/>
			   <soapenv:Body>
				  <xsd:addrcardMsgRequest>
					 <partner>' . $this->config->get('shipping_omniva_username') . '</partner>
					 <sendAddressCardTo>response</sendAddressCardTo>
					 <barcodes>
					 <barcode>' . $barcodes . '</barcode>

					 </barcodes>
				  </xsd:addrcardMsgRequest>
			   </soapenv:Body>
			</soapenv:Envelope>';

		try {
		  $url = $this->config->get('shipping_omniva_api_url') . '/epmx/services/messagesService.wsdl';
		  $headers = array(
			"Content-type: text/xml;charset=\"utf-8\"",
			"Accept: text/xml",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"Content-length: " . strlen($xmlRequest),
		  );
		  $ch = curl_init();
		  curl_setopt($ch, CURLOPT_URL, $url);
		  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($ch, CURLOPT_HEADER, 0);
		  curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('shipping_omniva_username') . ":" . $this->config->get('shipping_omniva_password'));
		  curl_setopt($ch, CURLOPT_POST, 1);
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		  $xmlResponse = curl_exec($ch);
		  $debugData['result'] = $xmlResponse;
		} catch (\Exception $e) {
		  $errors[] = $e->getMessage() . ' ' . $e->getCode();
		  $xmlResponse = '';
		}
		$xmlResponse = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $xmlResponse);
		$xml = simplexml_load_string($xmlResponse);
		if (!is_object($xml)) {
		  $errors[] = 'Response is in the wrong format';
		}

		if (is_object($xml) && is_object($xml->Body->addrcardMsgResponse->successAddressCards->addressCardData->barcode)) {
		  $shippingLabelContent = (string) $xml->Body->addrcardMsgResponse->successAddressCards->addressCardData->fileData;
		  file_put_contents(DIR_DOWNLOAD . 'omniva_' . $order_id . '.pdf', base64_decode($shippingLabelContent));
		} else {
		  $errors[] = 'No label received from webservice';
		}

		if (!empty($errors)) {
		  return array('status' => false, 'msg' => implode('. ', $errors));
		}
		if (!empty($barcodes)) {
		  return array('status' => true);
		}

		$errors[] = 'No saved barcodes received';
		return array('status' => false, 'msg' => implode('. ', $errors));
	}

	public function setOmnivaOrder($id_order = '', $tracking_nr = '', $omniva_weight = '', $labels_count = 1) {
		$json = array();
		
		if (!$id_order) {
			$id_order = $this->request->get['order_id'];
			$tracking_nr = '';
			$omniva_weight = $this->request->post['order_weight'];
			$labels_count = $this->request->post['order_labels_count'];
		}
		
		$order_omniva = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_omniva` WHERE order_id = '" . $id_order . "'");

		if ($order_omniva->num_rows > 0) {
			$trackingArr = json_decode($order_omniva->row['tracking_nr']);
			if (!is_array($trackingArr)) {
				$trackingArr = array();
			}
			array_unshift($trackingArr, $tracking_nr);
			
			$update_query = "UPDATE `" . DB_PREFIX . "order_omniva` SET ";
			if ($tracking_nr) $update_query .= "tracking_nr = '" . $this->db->escape(json_encode($trackingArr)) . "', ";
			$update_query .= "labels_count = '" . (int)($labels_count) . "', omniva_weight = '" . (float)($omniva_weight) . "' WHERE order_id = '" . $id_order . "'";
			$this->db->query($update_query);
		} else if ($order_omniva->num_rows == 0) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_omniva` SET order_id = '" . (int)$id_order . "', tracking_nr = '" . $this->db->escape($tracking_nr) . "', labels_count = '" . (int)$labels_count . "', omniva_weight = '" . (float)$omniva_weight . "'");
		};

		$json['success'] = 'Success: You have modified Omniva information!';

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	private function sendNotification($id_order, $tracking_nr = '')
	{
		$this->load->model('sale/order');

		try {
			$order = $this->model_sale_order->getOrder($id_order);
			$omniva_message = '' . $tracking_nr;

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			$subject = $this->config->get('config_name') . ' Omniva ' . $tracking_nr;
			$message = $omniva_message;
			$mail->setTo($order['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		} catch (Exception $e) {
			$this->log->write('Mail was not send to this order' . $e);
		}
	}

	private function cod($order, $cod = 0, $amount = 0) {
		$company = $this->config->get('shipping_omniva_company');
		$bank_account = $this->config->get('shipping_omniva_bank');
		if ($cod && $this->config->get('shipping_omniva_cod_status')) {
		  return '<monetary_values>
						<cod_receiver>' . $company . '</cod_receiver>
						<values code="item_value" amount="' . $amount . '"/>
						</monetary_values>
						<account>' . $bank_account . '</account>
						<reference_number>' . $this->getReferenceNumber($order['order_id']) . '</reference_number>';
		}
		return '';
	}
  
	private function getOrderWeight($order_id) {
		$query = $this->db->query(
			"
			SELECT SUM(IF(wcd.unit ='g',(p.weight/1000),p.weight) * op.quantity) AS weight 
			FROM `" . DB_PREFIX . "order_product` op 
			LEFT JOIN `" . DB_PREFIX . "product` p ON op.product_id = p.product_id 
			LEFT JOIN `" . DB_PREFIX . "weight_class_description` wcd ON wcd.weight_class_id = p.weight_class_id AND wcd.language_id = '" . (int) $this->config->get('config_language_id') . "' 
			WHERE op.order_id = '" . (int) $order_id . "'
			"
		);
		$weight = 1;
		if ($query->row['weight']) {
			$weight = $query->row['weight'];
		}

		return $weight;
	}
	
	protected static function getReferenceNumber($order_number) {
		$order_number = (string) $order_number;
		$kaal = array(7, 3, 1);
		$sl = $st = strlen($order_number);
		// makesure its at least 2 symbols
		$order_number = ($sl < 2 ? '0' : '') . (string) $order_number;
		$total = 0;
		while ($sl > 0 and substr($order_number, --$sl, 1) >= '0') {
		  $total += substr($order_number, ($st - 1) - $sl, 1) * $kaal[($sl % 3)];
		}
		$kontrollnr = ((ceil(($total / 10)) * 10) - $total);
		return $order_number . $kontrollnr;
	}

	public function api_request($request) {
		$barcodes = array();
		$errors = array();
		$url = $this->config->get('shipping_omniva_api_url') . '/epmx/services/messagesService.wsdl';

		$headers = array(
		  "Content-type: text/xml;charset=\"utf-8\"",
		  "Accept: text/xml",
		  "Cache-Control: no-cache",
		  "Pragma: no-cache",
		  "Content-length: " . strlen($request),
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('shipping_omniva_username') . ":" . $this->config->get('shipping_omniva_password'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$xmlResponse = curl_exec($ch);
	//file_put_contents(DIR_LOGS.'svlog_omniva_api_request1.txt', print_r($xmlResponse,1), FILE_APPEND);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	//file_put_contents(DIR_LOGS.'svlog_omniva_api_request2.txt', print_r($httpcode,1), FILE_APPEND);
		if ($xmlResponse === false || $httpcode != '200') {
		  $errors[] = curl_error($ch) . ' HTTP Code: ' . $httpcode;
		} else {
		  $errorTitle = '';
		  if (strlen(trim($xmlResponse)) > 0) {

			$xmlResponse = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $xmlResponse);
			$xml = simplexml_load_string($xmlResponse);
	//file_put_contents(DIR_LOGS.'svlog_omniva_api_request3.txt', print_r($xml,1), FILE_APPEND);
			if (!is_object($xml)) {
			  $errors[] = 'Response is in the wrong format';
			}
			if (is_object($xml) && is_object($xml->Body->businessToClientMsgResponse->faultyPacketInfo->barcodeInfo)) {
			  foreach ($xml->Body->businessToClientMsgResponse->faultyPacketInfo->barcodeInfo as $data) {
				$errors[] = $data->clientItemId . ' - ' . $data->barcode . ' - ' . $data->message;
			  }
			}
	//file_put_contents(DIR_LOGS.'svlog_omniva_api_request4.txt', print_r($errors,1), FILE_APPEND);
			if (empty($errors)) {
			  if (is_object($xml) && is_object($xml->Body->businessToClientMsgResponse->savedPacketInfo->barcodeInfo)) {
				foreach ($xml->Body->businessToClientMsgResponse->savedPacketInfo->barcodeInfo as $data) {
				  $barcodes[] = (string) $data->barcode;
				}
			  }
			}
	//file_put_contents(DIR_LOGS.'svlog_omniva_api_request5.txt', print_r($barcodes,1), FILE_APPEND);
		  }
		}
		if (!empty($errors)) {
		  return array('status' => false, 'msg' => implode('. ', $errors));
		}
		if (!empty($barcodes)) {
		  return array('status' => true, 'barcodes' => $barcodes);
		}
		$errors[] = 'No saved barcodes received';
		return array('status' => false, 'msg' => implode('. ', $errors));
	}
}
?>