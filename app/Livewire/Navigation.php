<?php

namespace App\Livewire;

use Livewire\Component;

class Navigation extends Component
{
    /**
     * The menu from config/navigation.php with URLs and active flags resolved.
     * A dropdown is active when any of its children is.
     *
     * @return array<int, array{label: string, url?: string, active: bool, children?: array<int, array{label: string, url: string, active: bool}>}>
     */
    public function items(): array
    {
        return array_map(function (array $item): array {
            if (! isset($item['children'])) {
                return $this->resolveLink($item);
            }

            $children = array_map(fn (array $child) => $this->resolveLink($child, $item['children']), $item['children']);

            return [
                'label' => $item['label'],
                'children' => $children,
                'active' => in_array(true, array_column($children, 'active'), true),
            ];
        }, config('navigation'));
    }

    /**
     * @param  array{label: string, route: string, params?: array<string, string>}  $link
     * @param  array<int, array<string, mixed>>  $siblings
     * @return array{label: string, url: string, active: bool}
     */
    protected function resolveLink(array $link, array $siblings = []): array
    {
        $params = $link['params'] ?? [];

        return [
            'label' => $link['label'],
            'url' => route($link['route'], $params),
            'active' => request()->routeIs($link['route']) && $this->matchesQuery($link, $siblings),
        ];
    }

    /**
     * A link with params is active only when the query carries them; a plain
     * link is not active while a sibling on the same route claims the query
     * (so "المطبوعات" and "الإصدارات" are never both highlighted).
     *
     * @param  array<string, mixed>  $link
     * @param  array<int, array<string, mixed>>  $siblings
     */
    protected function matchesQuery(array $link, array $siblings): bool
    {
        $params = $link['params'] ?? [];

        if ($params !== []) {
            return collect($params)->every(fn ($value, $key) => request()->query($key) === $value);
        }

        return collect($siblings)
            ->filter(fn ($sibling) => $sibling['route'] === $link['route'] && ! empty($sibling['params']))
            ->every(fn ($sibling) => collect($sibling['params'])->keys()->every(fn ($key) => request()->query($key) === null));
    }

    public function render()
    {
        return view('livewire.navigation', ['items' => $this->items()]);
    }
}
