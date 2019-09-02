import json


def file_to_json(path):
    with open(path, 'r') as f:
        data = f.read()
    json_data = json.loads(data)
    return json_data


class PackSwitcher(object):
    def numbers_to_packname(self, argument):
        """Dispatch method"""
        method_name = 'pack_' + str(argument)
        method = getattr(self, method_name, lambda: "Invalid number")
        return method()

    def pack_0(self):
        return "mythic"

    def pack_1(self):
        return "heroique"

    def pack_2(self):
        return "normal"


class ItemsSwitcher(object):
    def numbers_to_itemsname(self, argument):
        """Dispatch method"""
        method_name = 'items_' + str(argument)
        method = getattr(self, method_name, lambda: "Invalid number")
        return method()

    def items_0(self):
        return "ski"

    def items_1(self):
        return "snow"

    def items_2(self):
        return "ski_seul"

    def items_3(self):
        return "snow_seul"