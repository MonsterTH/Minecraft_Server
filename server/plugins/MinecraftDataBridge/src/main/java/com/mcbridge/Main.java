package com.mcbridge;

import org.bukkit.plugin.java.JavaPlugin;

public class Main extends JavaPlugin {

    @Override
    public void onEnable() {
        getServer().getPluginManager().registerEvents(new MinecraftListener(this), this);
        getLogger().info("MinecraftDataBridge ativo");
    }
}