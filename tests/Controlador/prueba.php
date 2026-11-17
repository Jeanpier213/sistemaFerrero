<?php
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
 
/**
 * UT-01 a UT-05 : loginControlador::iniciarSesion  (RF-01, RF-21, RNF-02)
 * Cada prueba corre en un proceso aparte porque el controlador llama a
 * session_start() al cargarse y porque Modelo/login.php define la clase Usuario
 * (la misma que usuarioModelo.php).
 */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
class LoginTest extends PruebaBase
{
    private loginControlador $ctrl;
 
    protected function setUp(): void
    {
        parent::setUp();
        ini_set('session.use_cookies', '0');
        ini_set('session.use_only_cookies', '0');
        ini_set('session.cache_limiter', '');
        require_once RAIZ . '/Controlador/login_controlador.php';
        $this->ctrl = new loginControlador();
    }
 
    /** UT-01 Login con credenciales válidas (clave cifrada con bcrypt) */
    public function test_UT01_login_valido(): void
    {
        $id = $this->crearUsuario('test_admin', password_hash('Clave123', PASSWORD_DEFAULT));
 
        $resultado = $this->ctrl->iniciarSesion('test_admin', 'Clave123');
 
        $this->assertTrue($resultado);
        $this->assertSame('test_admin', $_SESSION['usuario']);
        $this->assertEquals($id, $_SESSION['idUsuario']);
    }
 
    /** UT-02 Contraseña incorrecta */
    public function test_UT02_clave_incorrecta(): void
    {
        $this->crearUsuario('test_admin', password_hash('Clave123', PASSWORD_DEFAULT));
 
        $resultado = $this->ctrl->iniciarSesion('test_admin', 'ClaveMala');
 
        $this->assertSame('Contraseña incorrecta', $resultado);
        $this->assertArrayNotHasKey('usuario', $_SESSION);
    }
 
    /** UT-03 Cuenta desactivada (idEstado = 0) */
    public function test_UT03_cuenta_desactivada(): void
    {
        $this->crearUsuario('test_baja', password_hash('Clave123', PASSWORD_DEFAULT), 0);
 
        $resultado = $this->ctrl->iniciarSesion('test_baja', 'Clave123');
 
        $this->assertSame('Usuario desactivado', $resultado);
        $this->assertArrayNotHasKey('usuario', $_SESSION);
    }
 
    /** UT-04 Campos vacíos o usuario inexistente */
    #[DataProvider('casosUT04')]
    public function test_UT04_campos_vacios_o_usuario_inexistente(string $user, string $clave, string $esperado): void
    {
        $this->assertSame($esperado, $this->ctrl->iniciarSesion($user, $clave));
    }
 
    public static function casosUT04(): array
    {
        return [
            'usuario vacío'       => ['', 'abc123', 'Completa los campos'],
            'clave vacía'         => ['test_admin', '', 'Completa los campos'],
            'usuario inexistente' => ['test_no_existe_zzz', 'abc123', 'Usuario no encontrado'],
        ];
    }
 
    /** UT-05a Clave heredada en texto plano correcta: se acepta por compatibilidad (deuda H-03) */
    public function test_UT05a_clave_texto_plano_correcta(): void
    {
        $this->crearUsuario('test_plano', 'admin123');
 
        $this->assertTrue($this->ctrl->iniciarSesion('test_plano', 'admin123'));
        $this->assertSame('test_plano', $_SESSION['usuario']);
    }
 
    /** UT-05b Clave en texto plano incorrecta */
    public function test_UT05b_clave_texto_plano_incorrecta(): void
    {
        $this->crearUsuario('test_plano', 'admin123');
 
        $this->assertSame('Contraseña incorrecta', $this->ctrl->iniciarSesion('test_plano', 'otra'));
    }
}
