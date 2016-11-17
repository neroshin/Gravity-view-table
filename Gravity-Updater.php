class Gravity_Updater {
  protected $file;
  
  public function __construct( $file ) {
    $this->file = $file;
    return $this;
  }
}
