# 定义种子缓存文件路径
CACHE_FILE = "cache/seed.txt"
def add_seed_to_cache(seed):
    with open(CACHE_FILE, "a") as file:
        file.write(seed + "\n")

def get_cached_values():
    try:
        with open(CACHE_FILE, "r") as file:
            cached_values = file.read().splitlines()
    except FileNotFoundError:
        cached_values = []
    return cached_values
    
def remove_cached_values(arr):
    cached_values = set(get_cached_values())
    updated_arr = [x for x in arr if x not in cached_values]
    return updated_arr
