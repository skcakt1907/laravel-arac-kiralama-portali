<?php

namespace App\Services;

use App\Models\Part;
use Illuminate\Support\Collection;

/**
 * Session tabanlı sepet. Yapı: session('cart') = [part_id => qty].
 */
class Cart
{
    protected const KEY = 'cart';

    public function add(int $partId, int $qty = 1): void
    {
        $cart = $this->raw();
        $cart[$partId] = ($cart[$partId] ?? 0) + max(1, $qty);
        $this->save($cart);
    }

    public function update(int $partId, int $qty): void
    {
        $cart = $this->raw();
        if ($qty <= 0) {
            unset($cart[$partId]);
        } else {
            $cart[$partId] = $qty;
        }
        $this->save($cart);
    }

    public function remove(int $partId): void
    {
        $cart = $this->raw();
        unset($cart[$partId]);
        $this->save($cart);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    /** Sepetteki ürün adedi (toplam qty) */
    public function count(): int
    {
        return array_sum($this->raw());
    }

    /**
     * Sepet satırları: her biri ['part' => Part, 'qty' => int, 'line_total' => float].
     * Yayında olmayan / silinmiş parçalar otomatik elenir.
     */
    public function items(): Collection
    {
        $cart = $this->raw();
        if (empty($cart)) {
            return collect();
        }

        $parts = Part::published()->whereIn('id', array_keys($cart))->get()->keyBy('id');

        return collect($cart)
            ->filter(fn ($qty, $id) => $parts->has($id))
            ->map(fn ($qty, $id) => [
                'part'       => $parts[$id],
                'qty'        => (int) $qty,
                'line_total' => (float) $parts[$id]->price * (int) $qty,
            ])
            ->values();
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('line_total');
    }

    public function currency(): string
    {
        $first = $this->items()->first();

        return $first ? $first['part']->currency : 'USD';
    }

    public function isEmpty(): bool
    {
        return $this->items()->isEmpty();
    }

    protected function raw(): array
    {
        return session(self::KEY, []);
    }

    protected function save(array $cart): void
    {
        session()->put(self::KEY, $cart);
    }
}
