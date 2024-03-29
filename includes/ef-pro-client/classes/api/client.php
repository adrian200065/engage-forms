<?php


namespace engagewp\engageforms\pro\api;

use engagewp\engageforms\pro\container;
use engagewp\engageforms\pro\exceptions\Exception;


/**
 * Class client
 *
 * EF Pro API Client
 *
 * Both message abstractions decorate this object BTW
 *
 * @package engagewp\engageforms\pro
 */
class client extends api
{


	/**
	 * Create message on EF Pro
	 *
	 * @since 0.0.1
	 *
	 * @param \engagewp\engageforms\pro\api\message $message Message data
	 * @param bool $send Should message be sent immediately?
	 * @param int $entry_id Local entry ID
	 * @param string $type Optional. The message type. Default is "main" Options: main|auto
	 *
	 * @return \engagewp\engageforms\pro\message|\WP_Error|null
	 */
	public function create_message(
		\engagewp\engageforms\pro\api\message $message,
		$send,
		$entry_id,
		$type = 'main',
		array $anti_spam_args = null
	) {
		$data = $message->to_array();
		if ( $send ) {
			$data[ 'send' ] = true;
		}

		$anti_spam_args = container::get_instance()->get_anti_spam_args();
		if ( !empty($anti_spam_args) ) {
			$data[ 'antispam' ] = $anti_spam_args;
		} else {
			$data[ 'antispam' ] = false;
		}
		$response = $this->request('/message', $data, 'POST');
		if ( !is_wp_error($response) && 201 == wp_remote_retrieve_response_code($response) ) {
			$body = (array) json_decode(wp_remote_retrieve_body($response));
			if ( isset($body[ 'hash' ]) && isset($body[ 'id' ]) ) {
				try {
					$saved_message = container::get_instance()
						->get_messages_db()->create($body[ 'id' ], $body[ 'hash' ], $entry_id, $type);

					if ( !empty($anti_spam_args) && !empty($body[ 'spam_detected' ]) && true === rest_sanitize_boolean($body[ 'spam_detected' ]) ) {
						\Engage_Forms_Entry_Update::update_entry_status($entry_id, 'spam');
					}

					return $saved_message;
				} catch ( Exception $e ) {
					return $e->log([
						'type' => $type,
						'entry_id' => $entry_id,
						'send' => $send,
						'method' => __METHOD__,
						'pdf' => $message->pdf,
					])->to_wp_error();
				}

			}

		} elseif ( is_wp_error($response) ) {
			return $response;
		}

		return null;

	}


	/**
	 * Delete a message
	 *
	 * @param \engagewp\engageforms\pro\api\message $message Message db object
	 *
	 * @return bool
	 */
	public function delete_message(\engagewp\engageforms\pro\api\message $message)
	{
		return $this->_delete_message($message->get_efp_id());
	}

	/**
	 * Delete message by app message ID
	 *
	 * @since 0.1.0
	 *
	 * @param int $efp_id Remote ID
	 *
	 * @return bool
	 */
	public function delete_by_app_id($efp_id)
	{
		try {
			$message = container::get_instance()->get_messages_db()->get_by_remote_id($efp_id);
			return $this->delete_message($message);
		} catch ( Exception $e ) {
			return false;
		}


	}

	/**
	 * Delete by local DB (entry_id) entry ID
	 *
	 * @since 0.1.0
	 *
	 * @param int $entry_id Local ID
	 *
	 * @return bool
	 */
	public function delete_by_local_id($entry_id)
	{
		try {
			$message = container::get_instance()->get_messages_db()->get_by_entry_id($entry_id);
			return $this->delete_message($message);
		} catch ( Exception $e ) {
			return false;
		}

	}

	/**
	 * Get API keys
	 *
	 * @since 0.1.1
	 *
	 * @return keys
	 */
	public function get_keys()
	{
		return $this->keys;
	}

	/**
	 * Send previously saved EF Pro message
	 *
	 * @since 0.0.1
	 *
	 * @param int $message_id EF Pro ID of message
	 *
	 * @return array|\WP_Error
	 */
	public function send_saved($message_id)
	{
		return $this->request('/message/' . $message_id, [], 'POST');

	}

	/**
	 * Get PDF of previously saved message
	 *
	 * @since 0.0.1
	 *
	 * @param string $hash Hash of message
	 *
	 * @return array|\WP_Error
	 */
	public function get_pdf($hash)
	{
		return $this->request('/pdf/' . $hash, []);

	}

	/**
	 * Get HTML of previously saved message
	 *
	 * @since 0.0.1
	 *
	 * @param int $message_id Message ID (EF Pro ID)
	 *
	 * @return string
	 */
	public function get_html($message_id)
	{
		$r = $this->request('/message/view/' . $message_id, [], 'GET');
		if ( !is_wp_error($r) && 200 == wp_remote_retrieve_response_code($r) ) {
			return wp_remote_retrieve_body($r);
		}

	}

	/**
	 * @inheritdoc
	 */
	protected function get_url_root()
	{
		return engage_forms_pro_app_url();
	}

	/**
	 * Send delete request for message
	 *
	 * @since 0.10.0
	 *
	 * @param int $efp_id
	 *
	 * @return bool
	 */
	protected function _delete_message($efp_id)
	{
		$response = $this->request(sprintf('/message/%d', $efp_id), [], 'DELETE');
		if ( !is_wp_error($response) && 201 == wp_remote_retrieve_response_code($response) ) {
			return true;

		}

		return false;
	}


}
