<?php
class ControllerMailGdpr extends Controller {
	// catalog/model/customer/gdpr/addGdpr
	public function index(&$route, &$args, &$output) {
		$this->load->model('mail/customer');

		$customer_info = $this->model_customer_customer->getCustomer($args[0]);

		if ($customer_info) {
			$this->load->language('mail/gdpr');

			if ($this->config->get('config_logo')) {
				$data['logo'] = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
			} else {
				$data['logo'] = '';
			}

			$data['text_hello'] = sprintf($this->language->get('text_hello'), html_entity_decode($customer_info['firstname'], ENT_QUOTES, 'UTF-8'));
			$data['text_limit'] = sprintf($this->language->get('text_limit'), $this->config->get('config_gdpr_limit'));
			$data['text_a'] = sprintf($this->language->get('text_a'), $this->config->get('config_gdpr_limit'));

			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($this->load->view('mail/gdpr_approve', $data));
			$mail->send();
		}
	}
}
