<?php

namespace setasign\tests\functional\SetaFpdf\Exception;

use setasign\SetaFpdf\SetaFpdf;
use setasign\tests\TestCase;

class ConstructTest extends TestCase
{
    private function construct($orientation, $unit, $size)
    {
        try {
            new \FPDF($orientation, $unit, $size);
            $this->fail();
        // required for php5.6
        } catch (\Exception $e) {
            $error = $e;
        } catch (\Throwable $e) {
            $error = $e;
        }

        try {
            new SetaFpdf($orientation, $unit, $size);
            $this->fail();
        // required for php5.6
        } catch (\Exception $e) {
            $error2 = $e;
        } catch (\Throwable $e) {
            $error2 = $e;
        }

        return [
            'error' => $error,
            'error2' => $error2
        ];
    }

    private function validate($exceptions)
    {
        $this->assertInstanceOf(\Exception::class, $exceptions['error']);
        $this->assertInstanceOf(\Exception::class, $exceptions['error2']);
        throw $exceptions['error2'];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown orientation: hallo
     */
    public function testWithInvalidOrientation()
    {
        $this->validate($this->construct('hallo', 'mm', 'A4'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Incorrect unit: hallo
     */
    public function testWithInvalidUnit()
    {
        $this->validate($this->construct('P', 'hallo', 'A4'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid size array.
     */
    public function testWidthInvalidSize()
    {
        $this->validate($this->construct('P', 'mm', [2]));
    }
}