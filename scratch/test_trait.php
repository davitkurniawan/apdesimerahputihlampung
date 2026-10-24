<?php
trait T1 {
    public function foo() { echo "T1\n"; }
}
trait T2 {
    public function foo() { echo "T2\n"; }
}
class C {
    use T1, T2;
}
$c = new C();
$c->foo();
