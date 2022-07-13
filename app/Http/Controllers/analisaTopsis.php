<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Rtlh;
use Yajra\Datatables\Datatables;

class analisaTopsis extends Controller
{
    //

    public function get_trans()
    {
        $rtlh = \App\Model\Rtlh::all();
        //dinding
        foreach ($rtlh as $key) {
            if ($key->dinding == 1) {
                $key->l_dinding = 1;
            } elseif ($key->dinding == 2) {
                $key->l_dinding = 2;
            } elseif ($key->dinding == 3) {
                $key->l_dinding = 3;
            } elseif ($key->dinding == 4) {
                $key->l_dinding = 4;
            }
        }

        //Atap
        foreach ($rtlh as $key) {
            if ($key->atap == 1) {
                $key->l_atap = 1;
            } elseif ($key->atap == 2) {
                $key->l_atap = 2;
            } elseif ($key->atap == 3) {
                $key->l_atap = 3;
            } elseif ($key->atap == 4) {
                $key->l_atap = 4;
            }
        }
        //bahasa asing
        foreach ($rtlh as $key) {
            if ($key->lantai == 1) {
                $key->l_lantai = 1;
            } elseif ($key->lantai == 2) {
                $key->l_lantai = 2;
            } elseif ($key->lantai == 3) {
                $key->l_lantai = 3;
            } elseif ($key->lantai == 4) {
                $key->l_lantai = 4;
            }
        }

        //fmck
        foreach ($rtlh as $key) {
            if ($key->fmck == 1) {
                $key->l_fmck = 1;
            } elseif ($key->fmck == 2) {
                $key->l_fmck = 2;
            }
        }
        //luas lantai
        foreach ($rtlh as $key) {
            if ($key->luas_lantai == 1) {
                $key->l_luas_lantai = 1;
            } elseif ($key->luas_lantai == 2) {
                $key->l_luas_lantai = 2;
            } elseif ($key->luas_lantai == 3) {
                $key->l_luas_lantai = 3;
            } elseif ($key->luas_lantai == 4) {
                $key->l_luas_lantai = 4;
            }
        }

        //penghasilan
        foreach ($rtlh as $key) {
            if ($key->penghasilan == 1) {
                $key->l_penghasilan = 1;
            } elseif ($key->penghasilan == 2) {
                $key->l_penghasilan = 2;
            } elseif ($key->penghasilan == 3) {
                $key->l_penghasilan = 3;
            } elseif ($key->penghasilan == 4) {
                $key->l_penghasilan = 4;
            }
        }

        return $rtlh->all();
    }
    public function get_normalized()
    {
        $rtlh = $this->get_trans();
        $temp_dinding = 0;
        $temp_atap = 0;
        $temp_lantai = 0;
        $temp_fmck = 0;
        $temp_luas_lantai = 0;
        $temp_penghasilan = 0;
        foreach ($rtlh as $key) {
            $temp_dinding += $key->l_dinding * $key->l_dinding;
            $temp_atap += $key->l_atap * $key->l_atap;
            $temp_lantai += $key->l_lantai * $key->l_lantai;
            $temp_fmck += $key->l_fmck * $key->l_fmck;
            $temp_luas_lantai += $key->l_luas_lantai * $key->l_luas_lantai;
            $temp_penghasilan += $key->l_penghasilan * $key->l_penghasilan;
        }
        foreach ($rtlh as $key) {
            $key->r_dinding = $key->l_dinding / sqrt($temp_dinding);
            $key->r_atap = $key->l_atap / sqrt($temp_atap);
            $key->r_lantai = $key->l_lantai / sqrt($temp_lantai);
            $key->r_fmck = $key->l_fmck / sqrt($temp_fmck);
            $key->r_luas_lantai = $key->l_luas_lantai / sqrt($temp_luas_lantai);
            $key->r_penghasilan = $key->l_penghasilan / sqrt($temp_penghasilan);
        }

        return $rtlh;
    }

    public function get_terbobot()
    {
        $rtlh = $this->get_normalized();
        $options = \App\Model\Setting::getAllKeyValue();
        $c1 = json_decode($options['c1']);
        $c2 = json_decode($options['c2']);
        $c3 = json_decode($options['c3']);
        $c4 = json_decode($options['c4']);
        $c5 = json_decode($options['c5']);
        $c6 = json_decode($options['c6']);
        foreach ($rtlh as $key) {
            $key->v_dinding = $key->r_dinding * $c1->weight;
            $key->v_atap = $key->r_atap * $c2->weight;
            $key->v_lantai = $key->r_lantai * $c3->weight;
            $key->v_fmck = $key->r_fmck * $c4->weight;
            $key->v_luas_lantai = $key->r_luas_lantai * $c5->weight;
            $key->v_penghasilan = $key->r_penghasilan * $c5->weight;
        }

        return $rtlh;
    }
    public function get_ideal()
    {
        $options = \App\Model\Setting::getAllKeyValue();
        $c1 = json_decode($options['c1']);
        $c2 = json_decode($options['c2']);
        $c3 = json_decode($options['c3']);
        $c4 = json_decode($options['c4']);
        $c5 = json_decode($options['c5']);
        $c6 = json_decode($options['c6']);

        $rtlh = $this->get_terbobot();
        $temp_dinding = [];
        $temp_atap = [];
        $temp_lantai = [];
        $temp_fmck = [];
        $temp_luas_lantai = [];
        $temp_penghasilan = [];
        foreach ($rtlh as $key) {
            $temp_dinding[] = $key->v_dinding;
            $temp_atap[] = $key->v_atap;
            $temp_lantai[] = $key->v_lantai;
            $temp_fmck[] = $key->v_fmck;
            $temp_luas_lantai[] = $key->v_luas_lantai;
            $temp_penghasilan[] = $key->v_penghasilan;
        }

        $solusi = [
            'c1' => [
                'positif' => !$c1->is_cost
                    ? max($temp_dinding)
                    : min($temp_dinding),
                'negatif' => $c1->is_cost
                    ? max($temp_dinding)
                    : min($temp_dinding),
            ],
            'c2' => [
                'positif' => !$c2->is_cost ? max($temp_atap) : min($temp_atap),
                'negatif' => $c2->is_cost ? max($temp_atap) : min($temp_atap),
            ],
            'c3' => [
                'positif' => !$c3->is_cost
                    ? max($temp_lantai)
                    : min($temp_lantai),
                'negatif' => $c3->is_cost
                    ? max($temp_lantai)
                    : min($temp_lantai),
            ],
            'c4' => [
                'positif' => !$c4->is_cost ? max($temp_fmck) : min($temp_fmck),
                'negatif' => $c4->is_cost ? max($temp_fmck) : min($temp_fmck),
            ],
            'c5' => [
                'positif' => !$c5->is_cost
                    ? max($temp_luas_lantai)
                    : min($temp_luas_lantai),
                'negatif' => $c5->is_cost
                    ? max($temp_luas_lantai)
                    : min($temp_luas_lantai),
            ],
            'c6' => [
                'positif' => !$c6->is_cost
                    ? max($temp_penghasilan)
                    : min($temp_penghasilan),
                'negatif' => $c6->is_cost
                    ? max($temp_penghasilan)
                    : min($temp_penghasilan),
            ],
        ];

        return $solusi;
    }
    public function get_positif_distance()
    {
        $rtlh = $this->get_terbobot();
        $solusi_ideal = $this->get_ideal();
        foreach ($rtlh as $key) {
            $key->a_dinding = pow(
                $key->v_dinding - $solusi_ideal['c1']['positif'],
                2
            );
            $key->a_atap = pow(
                $key->v_atap - $solusi_ideal['c2']['positif'],
                2
            );
            $key->a_lantai = pow(
                $key->v_lantai - $solusi_ideal['c3']['positif'],
                2
            );
            $key->a_fmck = pow(
                $key->v_fmck - $solusi_ideal['c4']['positif'],
                2
            );
            $key->a_luas_lantai = pow(
                $key->v_luas_lantai - $solusi_ideal['c5']['positif'],
                2
            );
            $key->a_penghasilan = pow(
                $key->v_penghasilan - $solusi_ideal['c6']['positif'],
                2
            );
            $key->a_total = sqrt(
                $key->a_dinding +
                    $key->a_atap +
                    $key->a_lantai +
                    $key->a_fmck +
                    $key->a_luas_lantai +
                    $key->a_penghasilan
            );
        }
        return $rtlh;
    }
    public function get_negatif_distance()
    {
        $rtlh = $this->get_positif_distance();
        $solusi_ideal = $this->get_ideal();
        foreach ($rtlh as $key) {
            $key->b_dinding = pow(
                $key->v_dinding - $solusi_ideal['c1']['negatif'],
                2
            );
            $key->b_atap = pow(
                $key->v_atap - $solusi_ideal['c2']['negatif'],
                2
            );
            $key->b_lantai = pow(
                $key->v_lantai - $solusi_ideal['c3']['negatif'],
                2
            );
            $key->b_fmck = pow(
                $key->v_fmck - $solusi_ideal['c4']['negatif'],
                2
            );
            $key->b_luas_lantai = pow(
                $key->v_luas_lantai - $solusi_ideal['c5']['negatif'],
                2
            );
            $key->b_penghasilan = pow(
                $key->v_penghasilan - $solusi_ideal['c6']['negatif'],
                2
            );
            $key->b_total = sqrt(
                $key->b_dinding +
                    $key->b_atap +
                    $key->b_lantai +
                    $key->b_fmck +
                    $key->b_luas_lantai +
                    $key->b_penghasilan
            );
        }
        return $rtlh;
    }
    public function get_nilai_preferensi()
    {
        $rtlh = $this->get_negatif_distance();
        foreach ($rtlh as $key) {
            $key->nilai_preferensi =
                $key->b_total / ($key->a_total + $key->b_total);
        }
        return $rtlh;
    }

    public function matrix_keputusan()
    {
        $rtlh = $this->get_trans();
        return Datatables::of($rtlh)
            ->setRowId(function (Rtlh $rtlh) {
                return $rtlh->id;
            })
            ->make(true);
    }
    public function matrix_keputusan_ternormalisasi()
    {
        $rtlh = $this->get_normalized();
        return Datatables::of($rtlh)
            ->setRowId(function (Rtlh $rtlh) {
                return $rtlh->id;
            })
            ->make(true);
    }
    public function matrix_keputusan_terbobot()
    {
        $rtlh = $this->get_terbobot();
        return Datatables::of($rtlh)
            ->setRowId(function (Rtlh $rtlh) {
                return $rtlh->id;
            })
            ->make(true);
    }

    public function solusi_ideal()
    {
        $data['solusi'] = $this->get_ideal();
        return view('admin.topsis.matrix_solusi_ideal', $data);
    }

    public function jarak_solusi_positif()
    {
        $rtlh = $this->get_positif_distance();
        return Datatables::of($rtlh)
            ->setRowId(function (Rtlh $rtlh) {
                return $rtlh->id;
            })
            ->make(true);
    }
    public function jarak_solusi_negatif()
    {
        $rtlh = $this->get_negatif_distance();
        return Datatables::of($rtlh)
            ->setRowId(function (Rtlh $rtlh) {
                return $rtlh->id;
            })
            ->make(true);
    }
    public function nilai_preferensi()
    {
        $rtlh = $this->get_nilai_preferensi();
        return Datatables::of($rtlh)
            ->setRowId(function (Rtlh $rtlh) {
                return $rtlh->id;
            })
            // ->setRowClass(function (Rtlh $rtlh) {
            //     return $rtlh->nilai_preferensi > 0.41 ? 'success' : 'danger';
            // })
            ->make(true);
    }
}
