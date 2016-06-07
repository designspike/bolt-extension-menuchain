<?php

namespace Bolt\Extension\DesignSpike\MenuChain;

use Bolt\Application;
use Bolt\Extension\SimpleExtension;

class MenuChainExtension extends SimpleExtension
{
    public function registerTwigFunctions()
    {
        return [
            'menuchain_nodes' => 'twigMenuChainNodes',
            'menuchain_urls' => 'twigMenuChainUrls'
        ];
    }

    /**
     * Find the path to a given node in a navigation tree.  Result will be an array of menu nodes
     *
     * @param string $identifier - fully constructed menu array which is originally loaded from menu.yml
     * @param string $leaf_path - the "path", e.g. /page/about, which we are searching for in the menu
     * @return array - a list of items (arrays) starting from the top level of the tree (top level navigation) and ending at a point matching $leaf_path
     */
    public function twigMenuChainNodes($identifier, $leaf_path)
    {

        $menu = $this->getContainer()['menu']->menu($identifier)->getItems();

        return $this->findMenuChainNodes($menu, $leaf_path);

    }

    /**
     * Find the path to a given node in a navigation tree.  Result will be an array of url strings ["/page/about", "/page/staff", "/page/bob-jones"]
     *
     * @param string $identifier - fully constructed menu array which is originally loaded from menu.yml
     * @param string $leaf_path - the "path", e.g. /page/about, which we are searching for in the menu
     * @return array - a list of url strings starting from the top level of the tree (top level navigation) and ending at a point matching $leaf_path
     */
    public function twigMenuChainUrls($identifier, $leaf_path)
    {
        $node_chain = $this->twigMenuChainNodes($identifier, $leaf_path);

        $simple_chain = array();

        foreach ($node_chain as $node) {
            $simple_chain[] = $node['link'];
        }

        return $simple_chain;
    }

    /**
     * Find the path to a given node in a navigation tree.  Result will be an array of menu nodes
     *
     * @param array $menu - fully constructed menu array which is originally loaded from menu.yml
     * @param string $leaf_path - the "path", e.g. /page/about, which we are searching for in $menu
     * @param array $chain - the recursively passed chain that is built up into the final result
     * @return array - a list of items (arrays) starting from the top level of the tree (top level navigation) and ending at a point matching $needle
     */
    private function findMenuChainNodes(array $menu, $leaf_path, $chain = array())
    {
        foreach ($menu as $node) {
            if ($node['link'] == $leaf_path) {
                // if this is the target link in the chain, return the current chain with this appended
                return array_merge($chain, array($node));
            }
            elseif (is_array($node['submenu'])) {
                // search the submenus, adding current Node to the chain
                $subchain = $this->findMenuChainNodes($node['submenu'], $leaf_path, array_merge($chain, array($node)));
                if ($subchain) {
                    // if we found it in the submenus, return the chain we found
                    return $subchain;
                }
            }
        }
        // if we didn't find anything, return blank list
        return array();
    }
}
