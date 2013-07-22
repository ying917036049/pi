<?php
/**
 * Pi module installer resource
 *
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 * @author          Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 * @package         Pi\Application
 */

namespace Pi\Application\Installer\Resource;

use Pi;

/**
 * Event meta:
 *
 * <code>
 *  // Event list
 *  'events'    => array(
 *      // event name (unique)
 *      'user_call' => array(
 *          // title
 *          'title' => Pi::_('Event hook demo'),
 *      ),
 *  ),
 *  // Listener list
 *  'listeners' => array(
 *      array(
 *          // event info: module, event name
 *          'event'     => array('pm', 'test'),
 *          // listener info: class, method
 *          'listener'  => array('event', 'message'),
 *      ),
 *  ),
 * </code>
 */

class Event extends AbstractResource
{
    /**
     * Canonize listener data
     *
     * @param array $listener
     * @return array
     */
    protected function canonize($listener)
    {
        $module = $this->event->getParam('module');
        //$classPrefix = sprintf('Module\\%s', ucfirst($this->event->getParam('directory')));
        list($class, $method) = $listener['listener'];
        list($eventModule, $eventName) = $listener['event'];
        $data = array();
        $data['event_module']   = $eventModule;
        $data['event_name']     = $eventName;
        $data['module']         = $module;
        //$data['class']          = $classPrefix . '\\' . $class;
        $data['class']          = ucfirst($class);
        $data['method']         = $method;
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function installAction()
    {
        if (empty($this->config)) {
            return;
        }
        $module = $this->event->getParam('module');
        Pi::service('registry')->event->clear($module);

        $modelEvent = Pi::model('event');
        $events = isset($this->config['events']) ? $this->config['events'] : array();
        foreach ($events as $name => $event) {
            $event['module'] = $module;
            $event['name'] = $name;
            $status = $modelEvent->insert($event);
            if (!$status) {
                return array(
                    'status'    => false,
                    'message'   => sprintf('Event "%s" is not created.', $name)
                );
            }
        }

        $listeners = isset($this->config['listeners']) ? $this->config['listeners'] : array();
        $flushList = array();
        $modelListener = Pi::model('event_listener');
        foreach ($listeners as $listner) {
            $data = $this->canonize($listner);
            $status = $modelListener->insert($data);
            if (!$status) {
                return array(
                    'status'    => false,
                    'message'   => sprintf('Listener for event "%s" is not created.', $data['event_name'])
                );
            }
            $flushList[$data['event_module']] = 1;
        }
        foreach (array_keys($flushList) as $moduleName) {
            Pi::service('registry')->event->clear($moduleName);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function updateAction()
    {
        $module = $this->event->getParam('module');
        Pi::service('registry')->event->clear($module);

        if ($this->skipUpgrade()) {
            return;
        }

        $modelEvent = Pi::model('event');
        $modelListener = Pi::model('event_listener');

        $events = isset($this->config['events']) ? $this->config['events'] : array();
        $eventList = $modelEvent->select(array('module' => $module));
        foreach ($eventList as $row) {
            // Delete deprecated events
            if (!isset($events[$row->name])) {
                $row->delete();
                $status = true;
                if (!$status) {
                    return array(
                        'status'    => false,
                        'message'   => sprintf('Deprecated event "%s" is not deleted.', $row->name)
                    );
                }
                // Delete listeners
                $modelListener->delete(array('event_name' => $row->name, 'event_module' => $row->module));
                $status = true;
                if (!$status) {
                    return array(
                        'status'    => false,
                        'message'   => sprintf('Listeners for deprecated event "%s" are not deleted.', $row->name)
                    );
                }
                continue;
            }
            // Update event
            if ($row->title != $events[$row->name]['title']) {
                $row->title = $events[$row->name]['title'];
                $status = $row->save();
                if (!$status) {
                    return array(
                        'status'    => false,
                        'message'   => sprintf('Event "%s" is not updated.', $row->name)
                    );
                }
            }
            unset($events[$row->name]);
        }
        // Add new events
        foreach ($events as $name => $event) {
            $event['module'] = $module;
            $event['name'] = $name;
            $row = $modelEvent->createRow($event);
            $status = $row->save();
            if (!$status) {
                return array(
                    'status'    => false,
                    'message'   => sprintf('Event "%s" is not created.', $name)
                );
            }
        }

        $listeners = isset($this->config['listeners']) ? $this->config['listeners'] : array();
        $listenerList = array();
        //$classPrefix = sprintf('Module\\%s', ucfirst($this->event->getParam('directory')));
        foreach ($listeners as $listener) {
            $data = $this->canonize($listener);
            $key = $data['event_module'] . '-' . $data['event_name'] . '-' . $data['class'] . '-' . $data['method'];
            $listenerList[$key] = $data;
        }

        $rowset = $modelListener->select(array('module' => $module));
        $flushList = array();
        foreach ($rowset as $row) {
            $key = $row->event_module . '-' . $row->event_name . '-' . $row->class . '-' . $row->method;

            // Delete deprecated listeners
            if (!isset($listenerList[$key])) {
                $row->delete();
                $status = true;
                if (!$status) {
                    return array(
                        'status'    => false,
                        'message'   => sprintf('Deprecated listener "%s" is not deleted.', $key)
                    );
                }
                $flushList[$row->event_module] = 1;
            // Skip existent listeners
            } else {
                unset($listenerList[$key]);
            }
        }

        // Add new listeners
        foreach ($listenerList as $key => $data) {
            //$data = $this->canonize($data);
            $status = $modelListener->insert($data);
            if (!$status) {
                return array(
                    'status'    => false,
                    'message'   => sprintf('Listener "%s" is not created.', $key)
                );
            }
            $flushList[$data['event_module']] = 1;
        }
        foreach (array_keys($flushList) as $moduleName) {
            Pi::service('registry')->event->clear($moduleName);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function uninstallAction()
    {
        $module = $this->event->getParam('module');
        Pi::service('registry')->event->clear($module);

        $modelEvent = Pi::model('event');
        $modelListener = Pi::model('event_listener');
        $modelEvent->delete(array('module' => $module));
        $rowset = $modelListener->select(array('module' => $module));
        $modelListener->delete(array('module' => $module));
        foreach ($rowset as $row) {
            Pi::service('registry')->event->clear($row->event_module);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function activateAction()
    {
        $module = $this->event->getParam('module');
        Pi::service('registry')->event->clear($module);

        $modelEvent = Pi::model('event');
        $modelEvent->update(array('active' => 1), array('module' => $module));
        $modelListener = Pi::model('event_listener');
        $modelListener->update(array('active' => 1), array('module' => $module));
        $rowset = $modelListener->select(array('module' => $module));
        foreach ($rowset as $row) {
            Pi::service('registry')->event->clear($row->event_module);
        }
        Pi::service('registry')->event->clear($module);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function deactivateAction()
    {
        $module = $this->event->getParam('module');
        Pi::service('registry')->event->clear($module);

        $modelEvent = Pi::model('event');
        $modelEvent->update(array('active' => 0), array('module' => $module));
        $modelListener = Pi::model('event_listener');
        $modelListener->update(array('active' => 0), array('module' => $module));
        $rowset = $modelListener->select(array('module' => $module));
        foreach ($rowset as $row) {
            Pi::service('registry')->event->clear($row->event_module);
        }
        Pi::service('registry')->event->clear($module);

        return true;
    }
}
