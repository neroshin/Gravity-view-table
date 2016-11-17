<?php
class Smashing_Updater {
  private $username;
  private $repository;
  private $authorize_token;
  private $github_response;

public function set_username( $username ) {
  $this->username = $username;
}
public function set_repository( $repository ) {
  $this->repository = $repository;
}
public function authorize( $token ) {
  $this->authorize_token = $token;
}

// Include our updater file
include_once( plugin_dir_path( __FILE__ ) . 'update.php');

$updater = new Gravity_Updater( __FILE__ ); // instantiate our class
$updater->set_username( 'rayman813' ); // set username
$updater->set_repository( 'smashing-updater-plugin' ); // set repo

private function get_repository_info() {
  if ( is_null( $this->github_response ) ) { // Do we have a response?
    $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository ); // Build URI
    if( $this->authorize_token ) { // Is there an access token?
        $request_uri = add_query_arg( 'access_token', $this->authorize_token, $request_uri ); // Append it
    }        
    $response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true ); // Get JSON and parse it
    if( is_array( $response ) ) { // If it is an array
        $response = current( $response ); // Get the first item
    }
    if( $this->authorize_token ) { // Is there an access token?
        $response['zipball_url'] = add_query_arg( 'access_token', $this->authorize_token, $response['zipball_url'] ); // Update our zip url with token
    }
    $this->github_response = $response; // Set it to our property  
  }
}
}
?>
