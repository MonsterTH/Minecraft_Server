package com.mcbridge;

import org.bukkit.event.EventHandler;
import org.bukkit.event.Listener;
import org.bukkit.event.player.*;
import org.bukkit.event.player.PlayerAdvancementDoneEvent;

import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;

public class MinecraftListener implements Listener {

    private final Main plugin;

    public MinecraftListener(Main plugin) {
        this.plugin = plugin;
    }

    private void send(String json) {
        new Thread(() -> {
            try {
                URL url = new URL("http://192.168.251.113:8000/api/minecraft/event");

                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/json");
                conn.setDoOutput(true);

                conn.getOutputStream().write(json.getBytes(StandardCharsets.UTF_8));

                conn.getResponseCode();
                conn.disconnect();

            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();
    }

    @EventHandler
    public void join(PlayerJoinEvent e) {
        send("{\"event\":\"join\",\"player\":\"" + e.getPlayer().getName() + "\"}");
    }

    @EventHandler
    public void quit(PlayerQuitEvent e) {
        send("{\"event\":\"quit\",\"player\":\"" + e.getPlayer().getName() + "\"}");
    }

    @EventHandler
    public void chat(AsyncPlayerChatEvent e) {
        send("{\"event\":\"chat\",\"player\":\"" + e.getPlayer().getName() +
                "\",\"message\":\"" + e.getMessage().replace("\"","'") + "\"}");
    }

    @EventHandler
    public void advancement(PlayerAdvancementDoneEvent e) {
        send("{\"event\":\"advancement\",\"player\":\"" +
                e.getPlayer().getName() +
                "\",\"advancement\":\"" +
                e.getAdvancement().getKey().getKey() + "\"}");
    }
}