<?php
class ModelExtensionShippingOmniva extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/omniva');

		$quote_data = array();
		$method_data = array();
		$total_cart_weight = 0;

		$tab_zones = array('lv','lt','ee');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");

		foreach ($query->rows as $result) {
		
			foreach ($tab_zones as $tab_zone) {
				if (($this->config->get('shipping_omniva_' . $tab_zone . '_status'))&&($this->config->get('shipping_omniva_' . $tab_zone . '_geo_zone_id') == $result['geo_zone_id'])) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
					
					if ($query->num_rows) {
						$status = true;
					} else {
						$status = false;
					}
				} else {
					$status = false;
				}
	
				if (!$this->config->get('shipping_omniva_services_parcel_terminal') && !$this->config->get('shipping_omniva_services_courier')) {
					$status = false;
				}

				if ($status) {
					$rates = explode(',', $this->config->get('shipping_omniva_' . $tab_zone . '_cost'));
					$rates_courier = explode(',', $this->config->get('shipping_omniva_' . $tab_zone . '_courier_cost'));
					$max_weight = $this->config->get('shipping_omniva_weight');
					$max_length = $this->config->get('shipping_omniva_length');
					$max_width = $this->config->get('shipping_omniva_width');
					$max_height = $this->config->get('shipping_omniva_height');
					$dimensions_omniva = array($max_length, $max_width, $max_height);
		            sort($dimensions_omniva);
					
					$cartProducts = $this->cart->getProducts();

					foreach ($cartProducts as $product) {
						$dimensions = array();
						$weight = $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
						$length = $this->length->convert($product['length'], $product['length_class_id'], $this->config->get('config_length_class_id'));
						$width = $this->length->convert($product['width'], $product['length_class_id'], $this->config->get('config_length_class_id'));
						$height = $this->length->convert($product['height'], $product['length_class_id'], $this->config->get('config_length_class_id'));

						$dimensions = array($length, $width, $height);
						sort($dimensions);

						if (($dimensions[0] <= $dimensions_omniva[0])&&($dimensions[1] <= $dimensions_omniva[1])&&($dimensions[2] <= $dimensions_omniva[2])) {
							$total_cart_weight = $total_cart_weight + $weight;
						} else {
							$status = false;
							break;
						}
					}
						
					if ($total_cart_weight > $max_weight) {
						$status = false;
					}

					if ($this->config->get('shipping_omniva_services_courier')) {
// omniva courier cost
						if ($this->config->get('shipping_omniva_' . $tab_zone . '_courier_free') && ($this->cart->getTotal() >= $this->tax->calculate((float)$this->config->get('shipping_omniva_' . $tab_zone . '_courier_free'), $this->config->get('shipping_omniva_' . $tab_zone . '_tax_class_id'), $this->config->get('config_tax')))) {
							$total_cost_courier = 0;
						} else {
							if ($status) {
								if (count($rates_courier) == 1) {
									foreach ($rates_courier as $rate_courier) {
										$data_courier = explode(':', $rate_courier);
										$cost_courier = $data_courier[1];
									}
								} else {
									foreach ($rates_courier as $rate_courier) {
										$data_courier = explode(':', $rate_courier);

										if ($data_courier[0] >= $total_cart_weight) {
											if (isset($data_courier[1])) {
												$cost_courier = $data_courier[1];
											}

											break;
										}
									}
								}
								$total_cost_courier = $cost_courier;
							}
						}
	// omniva courier cost end

						$quote_data['courier_' . $result['geo_zone_id']] = array(
							'code'         => 'omniva.courier_' . $result['geo_zone_id'],
							'title'        => $this->language->get('text_title_courier'),
							'cost'         => $total_cost_courier,
							'tax_class_id' => $this->config->get('shipping_omniva_' . $tab_zone . '_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($total_cost_courier, $this->config->get('shipping_omniva_' . $tab_zone . '_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
						);
					}
					
// omniva terminals cost
					if ($this->config->get('shipping_omniva_services_parcel_terminal')) {
						if ($this->config->get('shipping_omniva_' . $tab_zone . '_free') && ($this->cart->getTotal() >= $this->tax->calculate((float)$this->config->get('shipping_omniva_' . $tab_zone . '_free'), $this->config->get('shipping_omniva_' . $tab_zone . '_tax_class_id'), $this->config->get('config_tax')))) {
							$total_cost = 0;
						} else {
							if ($status) {
								if (count($rates) == 1) {
									foreach ($rates as $rate) {
										$data = explode(':', $rate);
										$cost = $data[1];
									}
								} else {
									foreach ($rates as $rate) {
										$data = explode(':', $rate);

										if ($data[0] >= $total_cart_weight) {
											if (isset($data[1])) {
												$cost = $data[1];
											}

											break;
										}
									}
								}
								$total_cost = $cost;
							}
						}
					}
// omniva terminals cost end

//					if (!$this->config->get('shipping_omniva_services_parcel_terminal') && !$this->config->get('shipping_omniva_services_courier')) {
//						$status = false;
//					}
					
					if ($status) {
						$method_data = array(
							'code'       => 'omniva',
							'title'      => $this->language->get('text_title'),
							'quote'      => $quote_data,
							'parcel_terminal_status' => $this->config->get('shipping_omniva_services_parcel_terminal'),
							'sort_order' => $this->config->get('shipping_omniva_sort_order'),
							'cost'       => (isset($total_cost)) ? $total_cost : '',
							'text'       => (isset($total_cost)) ? $this->currency->format($this->tax->calculate($total_cost, $this->config->get('shipping_omniva_' . $tab_zone . '_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']) : '',
							'tax_class_id' => $this->config->get('shipping_omniva_' . $tab_zone . '_tax_class_id'),
							'choose_destination' => $this->language->get('text_choose_destination_om'),
							'error'      => false
						);
					}
				}
			}
		}
		if (isset($method_data)) return $method_data;
	}
	
	public function getGeoZones($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "geo_zone";

			$sort_data = array(
				'name',
				'description'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY name";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$geo_zone_data = $this->cache->get('geo_zone');

			if (!$geo_zone_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name ASC");

				$geo_zone_data = $query->rows;

				$this->cache->set('geo_zone', $geo_zone_data);
			}

			return $geo_zone_data;
		}
	}
	
	public function getAddress($address) {
		$this->load->language('extension/shipping/omniva');
		
		$ps_addresses = array();
		$omniva_addresses = array();
		
		$tab_zones = array('lv','lt','ee');
		$geo_zones = $this->getGeoZones();

		foreach ($geo_zones as $geo_zone) {
			foreach ($tab_zones as $tab_zone) {
				if (($this->config->get('shipping_omniva_' . $tab_zone . '_status'))&&($this->config->get('shipping_omniva_' . $tab_zone . '_geo_zone_id') == $geo_zone['geo_zone_id'])) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
					
					if ($query->num_rows) {
						$status = true;
					} else {
						$status = false;
					}
				} else {
					$status = false;
				}

				if ($status) {
					$omniva_addresses = $this->cache->get('omniva_addresses_'.$tab_zone.'.catalog');

					if (!$omniva_addresses) {
						$cabins = file_get_contents($this->config->get('shipping_omniva_import_url'));
						$cabins = json_decode($cabins, true);

//						$i = 1;
						foreach ($cabins as $ps_zone_k => $ps_zone_v) {
							if ((strcasecmp($ps_zone_v['A0_NAME'], $tab_zone) == 0) && $tab_zone == 'ee' && $ps_zone_v['TYPE'] == '0') {
								$ps_addresses[$ps_zone_v['ZIP']] = $ps_zone_v['NAME'];
								if (isset($ps_zone_v['A5_NAME'])) $ps_addresses[$ps_zone_v['ZIP']] .= ' - ' . $ps_zone_v['A5_NAME'];
								if (isset($ps_zone_v['A7_NAME'])) $ps_addresses[$ps_zone_v['ZIP']] .= ' - ' . $ps_zone_v['A7_NAME'];
								$ps_addresses[$ps_zone_v['ZIP']] .= ' - Omniva';
//								$i++;
							} else if ((strcasecmp($ps_zone_v['A0_NAME'], $tab_zone) == 0) && $tab_zone != 'ee') {
								$ps_addresses[$ps_zone_v['ZIP']] = $ps_zone_v['NAME'];
								if (isset($ps_zone_v['A5_NAME'])) $ps_addresses[$ps_zone_v['ZIP']] .= ' - ' . $ps_zone_v['A5_NAME'];
								if (isset($ps_zone_v['A7_NAME'])) $ps_addresses[$ps_zone_v['ZIP']] .= ' - ' . $ps_zone_v['A7_NAME'];
								$ps_addresses[$ps_zone_v['ZIP']] .= ' - Omniva';
//								$i++;
							}
						}

	//file_put_contents(DIR_LOGS.'svlog_omniva_ps_addresses.txt', print_r($ps_addresses,1), FILE_APPEND);
//						usort($ps_addresses, 'strnatcmp');
						asort($ps_addresses);
					
						$omniva_addresses['omniva.omniva_0'] = $this->language->get('text_choose_destination_om');
						foreach ($ps_addresses as $ps_addresses_key => $ps_addresses_value) {		
							$omniva_addresses['omniva.omniva_'.$ps_addresses_key] = $ps_addresses_value;
						}	

						$this->cache->set('omniva_addresses_'.$tab_zone.'.catalog', $omniva_addresses);						
					}
				}
			}		
		}
//file_put_contents(DIR_LOGS.'svlog_omniva_omniva_addresses.txt', print_r($omniva_addresses,1), FILE_APPEND);
		return $omniva_addresses;
	}
}
?>